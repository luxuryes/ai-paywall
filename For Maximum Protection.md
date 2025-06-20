## For Maximum Protection (Advanced)

The AI Paywall plugin automatically blocks the most important crawlers (`GPTBot`, `CCBot`, and a general `*` rule) from your content API. This provides excellent baseline protection.

However, for users who want the most comprehensive blocklist possible, you can manually add the following expanded rules to your site's `robots.txt` file. This list, inspired by major publishers like Robb Report, targets a wider array of AI, data-scraping, and next-gen search bots.

**Note:** This is a manual step. The plugin does not add these extra rules automatically.

```ini
# Comprehensive AI Blocklist
User-agent: anthropic-ai
Disallow: /wp-json/ai-paywall/

User-agent: cohere-ai
Disallow: /wp-json/ai-paywall/

User-agent: omgili
Disallow: /wp-json/ai-paywall/

User-agent: omgilibot
Disallow: /wp-json/ai-paywall/

User-agent: Applebot-Extended
Disallow: /wp-json/ai-paywall/

User-agent: Bytespider
Disallow: /wp-json/ai-paywall/

User-agent: CCBot
Disallow: /wp-json/ai-paywall/

User-agent: ChatGPT-User
Disallow: /wp-json/ai-paywall/

User-agent: DeepSeekBot
Disallow: /wp-json/ai-paywall/

User-agent: GPTBot
Disallow: /wp-json/ai-paywall/

User-agent: ClaudeBot
Disallow: /wp-json/ai-paywall/

User-agent: Diffbot
Disallow: /wp-json/ai-paywall/

User-agent: FacebookBot
Disallow: /wp-json/ai-paywall/

User-agent: Google-Extended
Disallow: /wp-json/ai-paywall/

User-agent: PerplexityBot
Disallow: /wp-json/ai-paywall/

User-agent: YouBot
Disallow: /wp-json/ai-paywall/

# General catch-all for any other bots
User-agent: *
Disallow: /wp-json/ai-paywall/
