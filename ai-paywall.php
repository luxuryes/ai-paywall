<?php
/**
 * Plugin Name:       AI Paywall
 * Description:       Hides content after a snippet from bots by loading it via JavaScript. Works automatically on old posts or manually with the "More" block.
 * Version:           1.0
 * Author:            Mircea G.
 * Author URI:        https://luxuryes.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// === SETTINGS PAGE & AUTOMATION LOGIC ===

function aip_add_settings_page() {
    add_options_page('AI Paywall Settings', 'AI Paywall', 'manage_options', 'ai-paywall', 'aip_settings_page_html');
}
add_action( 'admin_menu', 'aip_add_settings_page' );

function aip_sanitize_settings( $input ) {
    $sanitized_input = [];
    if ( isset( $input['auto_enable'] ) ) {
        $sanitized_input['auto_enable'] = '1';
    }
    if ( isset( $input['target_word_count'] ) ) {
        $sanitized_input['target_word_count'] = absint( $input['target_word_count'] );
    }
    return $sanitized_input;
}

function aip_register_settings() {
    register_setting( 'ai-paywall-group', 'aip_settings', 'aip_sanitize_settings' );
}
add_action( 'admin_init', 'aip_register_settings' );

function aip_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $options = get_option( 'aip_settings' );
    $auto_enable = isset( $options['auto_enable'] ) ? $options['auto_enable'] : '';
    $target_word_count = isset( $options['target_word_count'] ) ? (int) $options['target_word_count'] : 160;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p>Configure the AI Paywall to automatically protect old posts that do not have a manual "More" tag.</p>
        <form action="options.php" method="post">
            <?php settings_fields( 'ai-paywall-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="aip_auto_enable">Automatic Protection</label></th>
                    <td>
                        <input type="checkbox" id="aip_auto_enable" name="aip_settings[auto_enable]" value="1" <?php checked( '1', $auto_enable ); ?>>
                        <label for="aip_auto_enable">Enable for posts without a manual "More" tag</label>
                        <p class="description">When checked, the plugin will automatically create a snippet for old posts.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="aip_target_word_count">Snippet Target Length</label></th>
                    <td>
                        <input type="number" id="aip_target_word_count" name="aip_settings[target_word_count]" value="<?php echo esc_attr( $target_word_count ); ?>" min="50" max="500" class="small-text">
                        <label for="aip_target_word_count">words (approximate)</label>
                        <p class="description">The plugin will include full paragraphs until the word count meets or exceeds this target.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Settings' ); ?>
        </form>
    </div>
    <?php
}

// === HELPER FUNCTION TO SPLIT CONTENT BY WORD COUNT ===
function aip_split_content_by_word_count( $content, $target_word_count ) {
    $snippet = '';
    $hidden = '';
    $current_word_count = 0;
    $content_split = false;
    $paragraphs = explode( '</p>', $content );
    if (count($paragraphs) <= 1) { return ['snippet' => $content, 'hidden' => '', 'split' => false]; }

    foreach ( $paragraphs as $index => $paragraph ) {
        if ( empty( trim( $paragraph ) ) ) continue;
        $p_with_tag = $paragraph . '</p>';
        $word_count = str_word_count( wp_strip_all_tags( $p_with_tag ) );

        if ( $current_word_count < $target_word_count ) {
            $snippet .= $p_with_tag;
            $current_word_count += $word_count;
        } else {
            $hidden_array = array_slice($paragraphs, $index);
            $hidden = implode('</p>', $hidden_array);
            $content_split = true;
            break;
        }
    }
    
    if (!$content_split || empty(trim(wp_strip_all_tags($hidden)))) { return ['snippet' => $content, 'hidden' => '', 'split' => false]; }
    return ['snippet' => $snippet, 'hidden' => $hidden, 'split' => true];
}

// === CORE LOGIC ===
function aip_filter_content( $content ) {
    if ( is_singular() && in_the_loop() && is_main_query() ) {
        $post_id = get_the_ID();
        if ( strpos( $content, '<!--more-->' ) ) {
            $content_parts = get_extended( $content );
            $snippet = $content_parts['main'];
        } else {
            $options = get_option( 'aip_settings' );
            if ( !empty( $options['auto_enable'] ) ) {
                $target_word_count = isset( $options['target_word_count'] ) ? (int) $options['target_word_count'] : 160;
                $split_result = aip_split_content_by_word_count( $content, $target_word_count );
                if ($split_result['split']) { $snippet = $split_result['snippet']; } else { return $content; }
            } else { return $content; }
        }
        return $snippet . '<div id="ai-paywall-content" data-postid="' . esc_attr( $post_id ) . '"><p><em>Loading full content...</em></p></div>';
    }
    return $content;
}
add_filter( 'the_content', 'aip_filter_content' );

function aip_get_full_content( $request ) {
    $post_id = (int) $request['id'];
    $post = get_post( $post_id );
    if ( ! $post ) { return new WP_Error( 'not_found', 'Post not found', [ 'status' => 404 ] ); }
    $full_content = $post->post_content;
    $hidden_content_raw = '';
    if ( strpos( $full_content, '<!--more-->' ) ) {
        $content_parts = get_extended( $full_content );
        $hidden_content_raw = $content_parts['extended'];
    } else {
        $options = get_option( 'aip_settings' );
        if ( !empty( $options['auto_enable'] ) ) {
            $target_word_count = isset( $options['target_word_count'] ) ? (int) $options['target_word_count'] : 160;
            $split_result = aip_split_content_by_word_count( $full_content, $target_word_count );
            $hidden_content_raw = $split_result['hidden'];
        }
    }
    $hidden_content_formatted = apply_filters( 'the_content', $hidden_content_raw );
    return new WP_REST_Response( [ 'content' => $hidden_content_formatted ], 200 );
}

// === SCRIPT ENQUEUE ===
function aip_enqueue_scripts() {
    if ( is_singular() ) {
        wp_enqueue_script('ai-paywall-loader', plugin_dir_url( __FILE__ ) . 'loader.js', [], '1.0', true );
        wp_localize_script( 'ai-paywall-loader', 'aiPaywall', [ 'rest_url' => esc_url_raw( rest_url( 'ai-paywall/v1/content/' ) ), 'nonce' => wp_create_nonce( 'wp_rest' ) ] );
    }
}
add_action( 'wp_enqueue_scripts', 'aip_enqueue_scripts' );

// === REST API & ROBOTS.TXT ===
function aip_register_rest_route() {
    register_rest_route( 'ai-paywall/v1', '/content/(?P<id>\d+)', [ 'methods' => 'GET', 'callback' => 'aip_get_full_content', 'permission_callback' => '__return_true' ] );
}
add_action( 'rest_api_init', 'aip_register_rest_route' );

function aip_add_robots_txt_rules( $output ) {
    $output .= "\n# AI Paywall Plugin\n";
    $output .= "User-agent: GPTBot\nDisallow: /wp-json/ai-paywall/\n\n";
    $output .= "User-agent: CCBot\nDisallow: /wp-json/ai-paywall/\n\n";
    $output .= "User-agent: *\nDisallow: /wp-json/ai-paywall/\n";
    return $output;
}
add_filter( 'robots_txt', 'aip_add_robots_txt_rules', 20, 1 );
