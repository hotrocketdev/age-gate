<?php
/**
 * Plugin Name: HotRocket Age Gate
 * Plugin URI: https://hotrocket.dev
 * Description: Advanced age verification plugin with SEO-friendly bot detection, customizable restrictions, and multilingual support
 * Version: 2.0.0
 * Author: HotRocket
 * Author URI: https://hotrocket.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hotrocket-age-gate
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HOTROCKET_AGE_GATE_VERSION', '2.0.0');
define('HOTROCKET_AGE_GATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HOTROCKET_AGE_GATE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'includes/class-age-gate-bot-detector.php';
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'includes/class-age-gate-frontend.php';
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'includes/class-age-gate-shortcode.php';
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'includes/class-age-gate-metabox.php';
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'admin/class-age-gate-admin.php';

class HotRocket_Age_Gate {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
        $this->load_textdomain();
    }

    private function init_hooks() {
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Initialize frontend
        add_action('init', array($this, 'init_frontend'));

        // Initialize admin
        if (is_admin()) {
            add_action('admin_menu', array('HotRocket_Age_Gate_Admin', 'add_admin_menu'));
            add_action('admin_init', array('HotRocket_Age_Gate_Admin', 'register_settings'));
            
            // Initialize metabox
            new HotRocket_Age_Gate_Metabox();
        }
        
        // Initialize shortcode
        new HotRocket_Age_Gate_Shortcode();
    }

    /**
     * Load plugin textdomain for translations
     */
    private function load_textdomain() {
        load_plugin_textdomain(
            'hotrocket-age-gate',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function activate() {
        // Set default options
        $default_options = array(
            // General Settings
            'enabled' => 1,
            'age_limit' => 18,
            'cookie_duration' => 30,
            'redirect_url' => 'https://www.google.com',
            'show_remember' => 1,
            
            // Content Settings
            'welcome_title' => __('Welcome to Vin\'s Winery wine shop', 'hotrocket-age-gate'),
            'welcome_message' => __('Please confirm that you are over 18 years old to enter this site.', 'hotrocket-age-gate'),
            'button_yes_text' => __('Yes, I\'m 18+', 'hotrocket-age-gate'),
            'button_no_text' => __('No, Exit', 'hotrocket-age-gate'),
            'legal_note' => '',
            
            // Design Settings
            'custom_logo' => '',
            'logo_size' => 'medium',
            'popup_size' => 'large',
            'overlay_opacity' => 95,
            'overlay_color' => '#000000',
            'popup_bg_color' => '#ffffff',
            'primary_color' => '#722f37',
            'text_color' => '#333333',
            'button_text_color' => '#ffffff',
            
            // Advanced Settings
            'restriction_mode' => 'entire_site', // 'entire_site' or 'selected_content'
            'verification_method' => 'simple', // 'simple' or 'date_input'
            'date_format' => 'DD/MM/YYYY', // 'DD/MM/YYYY', 'MM/DD/YYYY', 'YYYY/MM/DD'
            'skip_logged_in' => 0,
            'custom_user_agents' => '',
            'disable_caching' => 0,
        );

        add_option('hotrocket_age_gate_options', $default_options);
    }

    public function init_frontend() {
        if (!is_admin()) {
            new HotRocket_Age_Gate_Frontend();
        }
    }
}

// Initialize the plugin
function HotRocket_Age_Gate() {
    return HotRocket_Age_Gate::get_instance();
}

HotRocket_Age_Gate();
