<?php
/**
 * Bot Detection for Age Gate
 * SEO-friendly bot detection to skip age verification for search engine crawlers
 */

if (!defined('ABSPATH')) {
    exit;
}

class HotRocket_Age_Gate_Bot_Detector {

    /**
     * Default list of common bots and crawlers
     */
    private static $default_bots = array(
        'googlebot',
        'bingbot',
        'slurp',          // Yahoo
        'duckduckbot',
        'baiduspider',
        'yandexbot',
        'sogou',
        'exabot',
        'facebot',        // Facebook
        'facebookexternalhit',
        'ia_archiver',    // Alexa
        'twitterbot',
        'linkedinbot',
        'pinterestbot',
        'whatsapp',
        'telegrambot',
        'applebot',
        'rogerbot',       // Moz
        'ahrefsbot',
        'semrushbot',
        'dotbot',
        'petalbot',
        'mj12bot',
        'screaming frog',
        'seznambot',
        'archive.org_bot',
        'ia_archiver',
        'msnbot',
        'adidxbot',
        'teoma',
        'ask jeeves',
        'jeeves',
        'spider',
        'crawler',
        'bot',
        'slurp',
        'wordpress',      // WordPress.com crawler
        'feedfetcher',    // Google Feedfetcher
        'postrank',
        'tumblr',
        'slack',
        'discordbot',
        'skypeuripreview',
        'vkshare',
        'w3c_validator',
        'redditbot',
        'embedly',
        'quora link preview',
        'showyoubot',
        'outbrain',
        'pinterest/0.',
        'developers.google.com/+/web/snippet',
        'slackbot',
        'vkshare',
        'w3c_validator',
        'whatsapp',
        'flipboard',
        'tumblr',
        'bitlybot',
        'skypeuripreview',
        'nuzzel',
        'discordbot',
        'qwantify',
        'pinterestbot',
        'bitrix link preview',
        'xing-contenttabreceiver',
        'chrome-lighthouse',
        'headlesschrome',
        'lighthouse',
    );

    /**
     * Check if current request is from a bot
     *
     * @return bool
     */
    public static function is_bot() {
        $user_agent = self::get_user_agent();
        
        if (empty($user_agent)) {
            return false;
        }

        // Get custom bots from settings
        $options = get_option('hotrocket_age_gate_options');
        $custom_bots = !empty($options['custom_user_agents']) ? $options['custom_user_agents'] : '';
        
        // Merge default and custom bots
        $all_bots = self::get_all_bots($custom_bots);
        
        // Apply filter to allow developers to modify bot list
        $all_bots = apply_filters('hotrocket_age_gate_bot_list', $all_bots);
        
        // Check if user agent matches any bot
        $user_agent_lower = strtolower($user_agent);
        
        foreach ($all_bots as $bot) {
            if (stripos($user_agent_lower, strtolower(trim($bot))) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get user agent string
     *
     * @return string
     */
    private static function get_user_agent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * Get all bots (default + custom)
     *
     * @param string $custom_bots Comma-separated custom bots
     * @return array
     */
    private static function get_all_bots($custom_bots = '') {
        $bots = self::$default_bots;
        
        if (!empty($custom_bots)) {
            // Split by comma or newline
            $custom_array = preg_split('/[,\n\r]+/', $custom_bots);
            $custom_array = array_map('trim', $custom_array);
            $custom_array = array_filter($custom_array); // Remove empty values
            
            $bots = array_merge($bots, $custom_array);
        }
        
        // Remove duplicates
        $bots = array_unique($bots);
        
        return $bots;
    }

    /**
     * Get default bot list
     *
     * @return array
     */
    public static function get_default_bots() {
        return self::$default_bots;
    }
}
