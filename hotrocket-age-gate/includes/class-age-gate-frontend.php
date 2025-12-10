<?php
/**
 * Frontend functionality for Age Gate
 */

if (!defined('ABSPATH')) {
    exit;
}

class HotRocket_Age_Gate_Frontend {

    private $options;

    public function __construct() {
        $this->options = get_option('hotrocket_age_gate_options');

        // Only load if enabled
        if (!empty($this->options['enabled'])) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_footer', array($this, 'render_age_gate'));
        }
    }

    public function enqueue_scripts() {
        // Check if user has already verified age
        if (isset($_COOKIE['hotrocket_age_verified'])) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'hotrocket-age-gate-css',
            VINS_AGE_GATE_PLUGIN_URL . 'assets/css/age-gate.css',
            array(),
            VINS_AGE_GATE_VERSION
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'hotrocket-age-gate-js',
            VINS_AGE_GATE_PLUGIN_URL . 'assets/js/age-gate.js',
            array('jquery'),
            VINS_AGE_GATE_VERSION,
            true
        );

        // Pass options to JavaScript
        wp_localize_script('hotrocket-age-gate-js', 'vinsAgeGate', array(
            'cookieDuration' => !empty($this->options['cookie_duration']) ? intval($this->options['cookie_duration']) : 30,
            'redirectUrl' => !empty($this->options['redirect_url']) ? esc_url($this->options['redirect_url']) : 'https://www.google.com',
            'ajaxUrl' => admin_url('admin-ajax.php')
        ));

        // Add inline styles for customization
        $this->add_custom_styles();
    }

    private function add_custom_styles() {
        $overlay_opacity = !empty($this->options['overlay_opacity']) ? intval($this->options['overlay_opacity']) : 95;
        $overlay_color = !empty($this->options['overlay_color']) ? sanitize_hex_color($this->options['overlay_color']) : '#000000';
        $popup_bg_color = !empty($this->options['popup_bg_color']) ? sanitize_hex_color($this->options['popup_bg_color']) : '#ffffff';
        $primary_color = !empty($this->options['primary_color']) ? sanitize_hex_color($this->options['primary_color']) : '#722f37';
        $text_color = !empty($this->options['text_color']) ? sanitize_hex_color($this->options['text_color']) : '#333333';
        $button_text_color = !empty($this->options['button_text_color']) ? sanitize_hex_color($this->options['button_text_color']) : '#ffffff';

        // Convert overlay opacity to decimal
        $overlay_opacity_decimal = $overlay_opacity / 100;

        // Convert hex to rgba
        list($r, $g, $b) = sscanf($overlay_color, "#%02x%02x%02x");
        $overlay_rgba = "rgba($r, $g, $b, $overlay_opacity_decimal)";

        $custom_css = "
            .hotrocket-age-gate-overlay {
                background-color: {$overlay_rgba} !important;
            }
            .hotrocket-age-gate-modal {
                background-color: {$popup_bg_color} !important;
            }
            .hotrocket-age-gate-modal h2,
            .hotrocket-age-gate-modal p,
            .hotrocket-age-gate-modal label {
                color: {$text_color} !important;
            }
            .hotrocket-age-gate-btn-yes {
                background-color: {$primary_color} !important;
                color: {$button_text_color} !important;
            }
            .hotrocket-age-gate-btn-yes:hover {
                opacity: 0.9;
            }
            .hotrocket-age-gate-btn-no {
                background-color: transparent !important;
                color: {$text_color} !important;
                border: 2px solid {$text_color} !important;
            }
            .hotrocket-age-gate-btn-no:hover {
                background-color: {$text_color} !important;
                color: {$popup_bg_color} !important;
            }
        ";

        wp_add_inline_style('hotrocket-age-gate-css', $custom_css);
    }

    public function render_age_gate() {
        // Check if user has already verified age
        if (isset($_COOKIE['hotrocket_age_verified'])) {
            return;
        }

        $welcome_title = !empty($this->options['welcome_title']) ? esc_html($this->options['welcome_title']) : 'Welcome to our site';
        $welcome_message = !empty($this->options['welcome_message']) ? esc_html($this->options['welcome_message']) : 'Please confirm that you are over 18 years old to enter this site.';
        $button_yes_text = !empty($this->options['button_yes_text']) ? esc_html($this->options['button_yes_text']) : 'Yes, I\'m 18+';
        $button_no_text = !empty($this->options['button_no_text']) ? esc_html($this->options['button_no_text']) : 'No, Exit';
        $show_remember = !empty($this->options['show_remember']) ? true : false;
        $age_limit = !empty($this->options['age_limit']) ? intval($this->options['age_limit']) : 18;
        $custom_logo = !empty($this->options['custom_logo']) ? esc_url($this->options['custom_logo']) : '';
        $logo_size = !empty($this->options['logo_size']) ? esc_attr($this->options['logo_size']) : 'medium';

        ?>
        <div class="hotrocket-age-gate-overlay" id="vinsAgeGateOverlay">
            <div class="hotrocket-age-gate-modal">
                <div class="hotrocket-age-gate-icon hotrocket-logo-size-<?php echo $logo_size; ?>">
                    <?php if ($custom_logo): ?>
                        <img src="<?php echo $custom_logo; ?>" alt="Logo" class="hotrocket-custom-logo">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 2h8l4 9H4l4-9z"/>
                            <path d="M12 11v11"/>
                            <path d="M8 22h8"/>
                            <ellipse cx="12" cy="8" rx="2" ry="3"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <h2><?php echo $welcome_title; ?></h2>
                <p class="hotrocket-age-gate-message"><?php echo $welcome_message; ?></p>

                <?php if ($show_remember): ?>
                <div class="hotrocket-age-gate-remember">
                    <label>
                        <input type="checkbox" id="vinsAgeGateRemember" checked>
                        <span>Remember me for <?php echo esc_html($this->options['cookie_duration']); ?> days</span>
                    </label>
                </div>
                <?php endif; ?>

                <div class="hotrocket-age-gate-buttons">
                    <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-yes" id="vinsAgeGateYes">
                        <?php echo $button_yes_text; ?>
                    </button>
                    <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-no" id="vinsAgeGateNo">
                        <?php echo $button_no_text; ?>
                    </button>
                </div>

                <p class="hotrocket-age-gate-footer">
                    By entering this site you are agreeing to the Terms of Use and Privacy Policy.
                </p>
            </div>
        </div>
        <?php
    }
}
