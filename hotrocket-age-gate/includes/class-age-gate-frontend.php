<?php
/**
 * Frontend functionality for Age Gate
 * Handles display logic, bot detection, and verification
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
            
            // Add no-cache headers if enabled
            if (!empty($this->options['disable_caching'])) {
                add_action('send_headers', array($this, 'add_no_cache_headers'));
            }
        }
    }

    /**
     * Add no-cache headers
     */
    public function add_no_cache_headers() {
        if ($this->should_show_age_gate()) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
    }

    /**
     * Determine if age gate should be shown
     *
     * @return bool
     */
    private function should_show_age_gate() {
        // Check if user has already verified age
        if (isset($_COOKIE['vins_age_verified'])) {
            return false;
        }

        // Check if bot (SEO friendly)
        if (HotRocket_Age_Gate_Bot_Detector::is_bot()) {
            return false;
        }

        // Check if logged-in users should be skipped
        if (!empty($this->options['skip_logged_in']) && is_user_logged_in()) {
            return false;
        }

        // Check restriction mode
        $restriction_mode = !empty($this->options['restriction_mode']) ? $this->options['restriction_mode'] : 'entire_site';
        
        if ($restriction_mode === 'selected_content') {
            // Only show on selected content
            if (is_singular()) {
                return HotRocket_Age_Gate_Metabox::requires_verification(get_the_ID());
            }
            return false;
        } else {
            // Show on entire site, but check for exclusions
            if (is_singular()) {
                $excluded = get_post_meta(get_the_ID(), '_HotRocket_Age_Gate_exclude', true);
                if ($excluded === '1') {
                    return false;
                }
            }
            return true;
        }
    }

    public function enqueue_scripts() {
        // Check if age gate should be shown
        if (!$this->should_show_age_gate()) {
            return;
        }

        // Enqueue CSS
        $css_version = !empty($this->options['disable_caching']) ? time() : HOTROCKET_AGE_GATE_VERSION;
        wp_enqueue_style(
            'hotrocket-age-gate-css',
            HOTROCKET_AGE_GATE_PLUGIN_URL . 'assets/css/age-gate.css',
            array(),
            $css_version
        );

        // Enqueue JavaScript
        $js_version = !empty($this->options['disable_caching']) ? time() : HOTROCKET_AGE_GATE_VERSION;
        wp_enqueue_script(
            'hotrocket-age-gate-js',
            HOTROCKET_AGE_GATE_PLUGIN_URL . 'assets/js/age-gate.js',
            array('jquery'),
            $js_version,
            true
        );

        // Get age limit (check for custom age on current post)
        $age_limit = $this->get_age_limit();

        // Get verification method
        $verification_method = !empty($this->options['verification_method']) ? $this->options['verification_method'] : 'simple';
        $date_format = !empty($this->options['date_format']) ? $this->options['date_format'] : 'DD/MM/YYYY';

        // Pass options to JavaScript
        wp_localize_script('hotrocket-age-gate-js', 'hotrocketAgeGate', array(
            'cookieDuration' => !empty($this->options['cookie_duration']) ? intval($this->options['cookie_duration']) : 30,
            'redirectUrl' => !empty($this->options['redirect_url']) ? esc_url($this->options['redirect_url']) : 'https://www.google.com',
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'ageLimit' => $age_limit,
            'verificationMethod' => $verification_method,
            'dateFormat' => $date_format,
        ));

        // Add inline styles for customization
        $this->add_custom_styles();
    }

    /**
     * Get age limit for current context
     *
     * @return int
     */
    private function get_age_limit() {
        $global_age = !empty($this->options['age_limit']) ? intval($this->options['age_limit']) : 18;
        
        // Check for custom age on current post
        if (is_singular()) {
            $custom_age = HotRocket_Age_Gate_Metabox::get_custom_age(get_the_ID());
            if ($custom_age) {
                return $custom_age;
            }
        }
        
        return $global_age;
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
        // Check if age gate should be shown
        if (!$this->should_show_age_gate()) {
            return;
        }

        // Apply filter to allow custom logic
        $should_show = apply_filters('hotrocket_age_gate_should_show', true, $this->options);
        if (!$should_show) {
            return;
        }

        $welcome_title = !empty($this->options['welcome_title']) ? esc_html($this->options['welcome_title']) : __('Welcome to Vin\'s Winery wine shop', 'hotrocket-age-gate');
        $welcome_message = !empty($this->options['welcome_message']) ? esc_html($this->options['welcome_message']) : __('Please confirm that you are over 18 years old to enter this site.', 'hotrocket-age-gate');
        $button_yes_text = !empty($this->options['button_yes_text']) ? esc_html($this->options['button_yes_text']) : __('Yes, I\'m 18+', 'hotrocket-age-gate');
        $button_no_text = !empty($this->options['button_no_text']) ? esc_html($this->options['button_no_text']) : __('No, Exit', 'hotrocket-age-gate');
        $show_remember = !empty($this->options['show_remember']) ? true : false;
        $age_limit = $this->get_age_limit();
        $custom_logo = !empty($this->options['custom_logo']) ? esc_url($this->options['custom_logo']) : '';
        $logo_size = !empty($this->options['logo_size']) ? esc_attr($this->options['logo_size']) : 'medium';
        $popup_size = !empty($this->options['popup_size']) ? esc_attr($this->options['popup_size']) : 'large';
        $legal_note = !empty($this->options['legal_note']) ? wp_kses_post($this->options['legal_note']) : '';
        $verification_method = !empty($this->options['verification_method']) ? $this->options['verification_method'] : 'simple';
        $date_format = !empty($this->options['date_format']) ? $this->options['date_format'] : 'DD/MM/YYYY';

        // Hook before modal
        do_action('hotrocket_age_gate_before_modal', $this->options);

        ob_start();
        ?>
        <div class="hotrocket-age-gate-overlay" id="hotrocketAgeGateOverlay">
            <div class="hotrocket-age-gate-modal vins-popup-size-<?php echo $popup_size; ?>">
                <div class="hotrocket-age-gate-icon vins-logo-size-<?php echo $logo_size; ?>">
                    <?php if ($custom_logo): ?>
                        <img src="<?php echo $custom_logo; ?>" alt="Logo" class="vins-custom-logo">
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

                <?php if ($verification_method === 'date_input'): ?>
                    <!-- Date Input Method -->
                    <div class="hotrocket-age-gate-date-input" id="hotrocketAgeGateDateInput">
                        <?php $this->render_date_inputs($date_format); ?>
                        <p class="hotrocket-age-gate-date-error" id="hotrocketAgeGateDateError" style="display: none; color: #d32f2f; font-size: 14px; margin-top: 10px;"></p>
                    </div>
                <?php endif; ?>

                <?php if ($show_remember): ?>
                <div class="hotrocket-age-gate-remember">
                    <label>
                        <input type="checkbox" id="hotrocketAgeGateRemember" checked>
                        <span><?php printf(__('Remember me for %d days', 'hotrocket-age-gate'), esc_html($this->options['cookie_duration'])); ?></span>
                    </label>
                </div>
                <?php endif; ?>

                <?php do_action('hotrocket_age_gate_before_buttons', $this->options); ?>

                <div class="hotrocket-age-gate-buttons">
                    <?php if ($verification_method === 'simple'): ?>
                        <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-yes" id="hotrocketAgeGateYes">
                            <?php echo $button_yes_text; ?>
                        </button>
                        <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-no" id="hotrocketAgeGateNo">
                            <?php echo $button_no_text; ?>
                        </button>
                    <?php else: ?>
                        <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-yes" id="hotrocketAgeGateVerifyDate">
                            <?php _e('Verify Age', 'hotrocket-age-gate'); ?>
                        </button>
                        <button class="hotrocket-age-gate-btn hotrocket-age-gate-btn-no" id="hotrocketAgeGateNo">
                            <?php echo $button_no_text; ?>
                        </button>
                    <?php endif; ?>
                </div>

                <?php do_action('hotrocket_age_gate_after_buttons', $this->options); ?>

                <?php if ($legal_note): ?>
                <div class="hotrocket-age-gate-legal-note">
                    <?php echo $legal_note; ?>
                </div>
                <?php else: ?>
                <p class="hotrocket-age-gate-footer">
                    <?php _e('By entering this site you are agreeing to the Terms of Use and Privacy Policy.', 'hotrocket-age-gate'); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        $html = ob_get_clean();
        
        // Apply filter to allow customization
        $html = apply_filters('hotrocket_age_gate_modal_html', $html, $this->options);
        
        echo $html;

        // Hook after modal
        do_action('hotrocket_age_gate_after_modal', $this->options);
    }

    /**
     * Render date input fields based on format
     *
     * @param string $format Date format (DD/MM/YYYY, MM/DD/YYYY, YYYY/MM/DD)
     */
    private function render_date_inputs($format) {
        $formats = array(
            'DD/MM/YYYY' => array('day', 'month', 'year'),
            'MM/DD/YYYY' => array('month', 'day', 'year'),
            'YYYY/MM/DD' => array('year', 'month', 'day'),
        );

        $order = isset($formats[$format]) ? $formats[$format] : $formats['DD/MM/YYYY'];

        echo '<div class="hotrocket-age-gate-date-fields">';
        
        foreach ($order as $field) {
            $this->render_date_field($field);
        }
        
        echo '</div>';
    }

    /**
     * Render individual date field
     *
     * @param string $field Field type (day, month, year)
     */
    private function render_date_field($field) {
        $labels = array(
            'day' => __('Day', 'hotrocket-age-gate'),
            'month' => __('Month', 'hotrocket-age-gate'),
            'year' => __('Year', 'hotrocket-age-gate'),
        );

        $placeholders = array(
            'day' => 'DD',
            'month' => 'MM',
            'year' => 'YYYY',
        );

        $maxlengths = array(
            'day' => 2,
            'month' => 2,
            'year' => 4,
        );

        ?>
        <div class="hotrocket-age-gate-date-field">
            <label for="hotrocketAgeGate<?php echo ucfirst($field); ?>"><?php echo $labels[$field]; ?></label>
            <input 
                type="number" 
                id="hotrocketAgeGate<?php echo ucfirst($field); ?>" 
                placeholder="<?php echo $placeholders[$field]; ?>" 
                maxlength="<?php echo $maxlengths[$field]; ?>"
                min="<?php echo $field === 'year' ? '1900' : '1'; ?>"
                max="<?php echo $field === 'year' ? date('Y') : ($field === 'day' ? '31' : '12'); ?>"
                class="hotrocket-age-gate-input-<?php echo $field; ?>"
            >
        </div>
        <?php
    }
}
