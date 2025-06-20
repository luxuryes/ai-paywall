# ai-paywall
Protect your full content from AI crawlers while keeping a public, SEO-friendly snippet for search engines.


=== AI Paywall ===
Contributors: luxuryes
Author URI: https://luxuryes.com/
Tags: ai, paywall, content protection, seo, gptbot
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect your full content from AI crawlers while keeping a public, SEO-friendly snippet for search engines.

== Description ==

In the age of AI, content creators face a new challenge: how to maintain an online presence without giving away their entire library to large language models (LLMs) for training, free of charge.

The **AI Paywall** plugin is a simple, lightweight, and powerful solution to this problem.

It creates a "soft paywall" that elegantly separates your content. You have two modes of operation:
*   **Manual Mode:** Use the standard WordPress "More" block to precisely control where your public snippet ends.
*   **Automatic Mode:** Let the plugin automatically protect your entire back catalog of posts by creating a snippet of a target word count (e.g., the first ~160 words).

The rest of your article, the valuable full content, is hidden from all bots (including Googlebot, GPTBot, CCBot, etc.) and is loaded dynamically only for human visitors.

**How It Works:**

1.  **Serve a Snippet:** The plugin initially serves a public teaser. This is either the content before your "More" tag or the first ~160 words of your post.
2.  **Attract Search Engines:** Bots see this snippet, index it, and rank it in search results, ensuring your site remains discoverable.
3.  **Load for Humans:** For human visitors, a tiny piece of non-intrusive JavaScript instantly fetches the rest of the article from a dedicated, secure API endpoint and displays it seamlessly.
4.  **Block the Bots:** The plugin automatically adds rules to your `robots.txt` file, preventing all crawlers from accessing the API endpoint that serves the full content.

**Key Features:**

*   **Protect Your Content:** Keep your full articles, analysis, and creative works out of AI training datasets.
*   **Maintain Your SEO:** Your site's discoverability is unaffected. You still appear in search results for keywords in your public snippet.
*   **Automatic Protection for Old Posts:** Enable automatic mode to instantly protect your entire library of existing content without editing a single post.
*   **Full Manual Control:** The manual "More" block always overrides the automatic settings, giving you fine-grained control when you need it.
*   **Lightweight & Performant:** The plugin is lean and only loads its small script on single posts and pages, having zero impact on your site's archive pages or homepage speed.

This is the perfect tool for bloggers, journalists, artists, and any creator who wants to showcase their work online without feeding the machine for free.

== Installation ==

**From your WordPress Dashboard (Recommended):**

1.  Go to 'Plugins' > 'Add New' in your WordPress dashboard.
2.  Search for "AI Paywall".
3.  Click 'Install Now' and then 'Activate'.

**Manual Upload:**

1.  Download the plugin .zip file from the WordPress.org repository.
2.  Go to 'Plugins' > 'Add New' and click the 'Upload Plugin' button.
3.  Upload the .zip file you downloaded and click 'Activate'.

**Usage & Configuration:**

1.  After activating, go to **Settings > AI Paywall**.
2.  To protect your old posts, check the box for **"Enable for posts without a manual 'More' tag"**.
3.  Set your desired **"Snippet Target Length"** in words (default is 160). This will apply to all posts in automatic mode.
4.  Save your settings.
5.  For new posts, you can either rely on the automatic setting or insert the **"More"** block in the editor for precise control.

== Frequently Asked Questions ==

= How is the snippet length determined in automatic mode? =

The plugin uses a word-count targeting system. On the settings page, you can set a "Target Word Count" (e.g., 160 words). For posts without a "More" tag, the plugin will include full paragraphs in the snippet until the total word count meets or exceeds your target. This ensures the snippet is a good length for SEO while never breaking your site's layout by cutting off text mid-sentence.

= Will this plugin hurt my SEO? =

No, this method is designed to be SEO-friendly. Google will see and index the public snippet. This allows you to rank for keywords in your title, meta description, and public content. However, it's important to remember that **Google cannot see or rank you for keywords that only appear in the protected content**. Your most important keywords should be in the public part.

= Is this considered "cloaking" by Google? =

No. This method uses a standard technique called "lazy loading" or "conditional content delivery," which is compliant with Google's guidelines. It is not deceptive cloaking because the initial content served is the same for both users and bots. The user simply gets *more* content loaded in after the fact, which is a common practice for paywalls and user experience enhancements.

= The rules are not appearing in my robots.txt file. Why? =

This can happen if your site has a physical `robots.txt` file in its main directory. A physical file will always override the virtual one that WordPress and this plugin use. To check, visit `https://yourdomain.com/robots.txt`.

You have two solutions:
1.  **(Recommended)** Delete the physical `robots.txt` file from your server's root directory using FTP or a file manager. This will allow the plugin to add its rules automatically and safely.
2.  **(Manual Fix)** If you must keep your physical `robots.txt` file, you need to add the following rules to it manually:

AI Paywall Plugin

User-agent: GPTBot
Disallow: /wp-json/ai-paywall/

User-agent: CCBot
Disallow: /wp-json/ai-paywall/

User-agent: *
Disallow: /wp-json/ai-paywall/
