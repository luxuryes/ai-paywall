# ai-paywall
Protect your full content from AI crawlers while keeping a public, SEO-friendly snippet for search engines.


=== AI Paywall ===
* Contributors: luxuryes
* Author URI: https://luxuryes.com/
* Tags: ai, paywall, content protection, seo, gptbot
* Requires at least: 5.5
* Tested up to: 6.8
* Requires PHP: 7.4
* Stable tag: 1.0
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html


# AI Paywall for WordPress

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/ai-paywall.svg)](https://wordpress.org/plugins/ai-paywall/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/ai-paywall.svg)](https://wordpress.org/plugins/ai-paywall/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/r/ai-paywall.svg)](https://wordpress.org/plugins/ai-paywall/reviews/)

**Download the official version from the [WordPress.org Plugin Directory](https://wordpress.org/plugins/ai-paywall/).**

Development of this plugin is managed on GitHub. Feel free to report issues or contribute to the project.

## Description

In the age of AI, content creators face a new challenge: how to maintain an online presence without giving away their entire library to large language models (LLMs) for training, free of charge. The **AI Paywall** plugin is a simple, lightweight, and powerful solution to this problem.

It creates a "soft paywall" that elegantly separates your content. You have two modes of operation:
*   **Manual Mode:** Use the standard WordPress `<!--more-->` block to precisely control where your public snippet ends.
*   **Automatic Mode:** Let the plugin automatically protect your entire back catalog of posts by creating a snippet of a target word count (e.g., the first ~160 words).

The rest of your article is hidden from all bots (including Googlebot, GPTBot, CCBot, etc.) and is loaded dynamically only for human visitors.

### How It Works

1.  **Serve a Snippet:** The plugin initially serves a public teaser.
2.  **Attract Search Engines:** Bots see this snippet and index it, ensuring your site remains discoverable.
3.  **Load for Humans:** For human visitors, JavaScript fetches the rest of the article from a dedicated, secure API endpoint.
4.  **Block the Bots:** The plugin automatically adds rules to your `robots.txt` file, preventing crawlers from accessing the full content.

## Key Features

*   **Protect Your Content:** Keep your full articles out of AI training datasets.
*   **Maintain Your SEO:** Your site's discoverability is unaffected by using an indexable snippet.
*   **Automatic Protection for Old Posts:** Instantly protect your entire library without editing a single post.
*   **Full Manual Control:** The manual `<!--more-->` block always overrides the automatic settings.
*   **Lightweight & Performant:** The plugin is lean and has zero impact on your site's archive pages or homepage speed.

## Installation & Usage

1.  Download and activate the plugin from the [WordPress.org directory](https://wordpress.org/plugins/ai-paywall/).
2.  Navigate to **Settings > AI Paywall**.
3.  To protect your old posts, check **"Enable for posts without a manual 'More' tag"**.
4.  Set your desired **"Snippet Target Length"** in words (default is 160).
5.  Save your settings. That's it!

## Frequently Asked Questions

### How is the snippet length determined in automatic mode?
The plugin uses a word-count targeting system. It will include full paragraphs in the snippet until the total word count meets or exceeds your target, ensuring it never breaks your site's layout by cutting off text mid-sentence.

### Will this plugin hurt my SEO?
No. Google will see and index the public snippet, allowing you to rank for keywords within it. However, Google cannot rank you for keywords that *only* appear in the protected content.

### Is this considered "cloaking" by Google?
No. This method uses a standard technique called "lazy loading" or "conditional content delivery," which is compliant with Google's guidelines and is a common practice for paywalls.

### The rules are not appearing in my robots.txt file. Why?
This can happen if your site has a physical `robots.txt` file in its main directory. A physical file will always override the virtual one that WordPress and this plugin use. To check, visit `https://yourdomain.com/robots.txt`.

You have two solutions:
1.  **(Recommended)** Delete the physical `robots.txt` file from your server's root directory using FTP or a file manager. This will allow the plugin to add its rules automatically and safely.
2.  **(Manual Fix)** If you must keep your physical `robots.txt` file, you need to add the following rules to it manually:

```ini
# AI Paywall Plugin
User-agent: GPTBot
Disallow: /wp-json/ai-paywall/

User-agent: CCBot
Disallow: /wp-json/ai-paywall/

User-agent: *
Disallow: /wp-json/ai-paywall/

```

For Maximum Protection (Advanced)

The AI Paywall plugin automatically blocks the most important crawlers (GPTBot, CCBot, and a general * rule) from your content API. This provides excellent baseline protection.

However, for users who want the most comprehensive blocklist possible, you can manually add the following expanded rules to your site's robots.txt file. This list, inspired by major publishers like Robb Report, targets a wider array of AI, data-scraping, and next-gen search bots.

Note: This is a manual step. The plugin does not add these extra rules automatically.
