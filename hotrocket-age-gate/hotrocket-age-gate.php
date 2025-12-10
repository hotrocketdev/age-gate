<?php
/**
 * Plugin Name: Hot Rocket Age Gate
 * Plugin URI: https://hotrocket.dev
 * Description: Age verification popup with modern UI and customizable design - perfect for wine shops, breweries, and age-restricted content
 * Version: 1.0.0
 * Author: Hot Rocket
 * Author URI: https://hotrocket.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hotrocket-age-gate
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HOTROCKET_AGE_GATE_VERSION', '1.0.0');
define('HOTROCKET_AGE_GATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HOTROCKET_AGE_GATE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once HOTROCKET_AGE_GATE_PLUGIN_DIR . 'includes/class-age-gate-frontend.php';
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
        }
    }

    public function activate() {
        // Set default options
        $default_options = array(
            'enabled' => 1,
            'age_limit' => 18,
            'welcome_title' => 'Welcome to our site',
            'welcome_message' => 'Please confirm that you are over 18 years old to enter this site.',
            'button_yes_text' => 'Yes, I\'m 18+',
            'button_no_text' => 'No, Exit',
            'cookie_duration' => 30,
            'redirect_url' => 'https://www.google.com',
            'show_remember' => 1,
            'custom_logo' => '',
            'logo_size' => 'medium',
            'overlay_opacity' => 95,
            'overlay_color' => '#000000',
            'popup_bg_color' => '#ffffff',
            'primary_color' => '#ff6b35',
            'text_color' => '#333333',
            'button_text_color' => '#ffffff'
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
function hotrocket_age_gate() {
    return HotRocket_Age_Gate::get_instance();
}

hotrocket_age_gate();
