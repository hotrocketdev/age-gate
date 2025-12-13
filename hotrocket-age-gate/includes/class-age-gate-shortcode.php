<?php
/**
 * Shortcode functionality for Age Gate
 * Allows content-specific age restrictions using [age_gate]content[/age_gate]
 */

if (!defined('ABSPATH')) {
    exit;
}

class HotRocket_Age_Gate_Shortcode {

    public function __construct() {
        add_shortcode('age_gate', array($this, 'age_gate_shortcode'));
        add_shortcode('age_restricted', array($this, 'age_gate_shortcode')); // Alias
    }

    /**
     * Shortcode callback
     * Usage: [age_gate age="21"]Content here[/age_gate]
     *
     * @param array $atts Shortcode attributes
     * @param string $content Enclosed content
     * @return string
     */
    public function age_gate_shortcode($atts, $content = null) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'age' => '', // Custom age limit for this content
            'message' => '', // Custom message to show when restricted
        ), $atts, 'age_gate');

        // Get plugin options
        $options = get_option('hotrocket_age_gate_options');
        
        // Determine age limit
        $age_limit = !empty($atts['age']) ? intval($atts['age']) : (!empty($options['age_limit']) ? intval($options['age_limit']) : 18);
        
        // Check if user is verified
        $is_verified = isset($_COOKIE['vins_age_verified']) && $_COOKIE['vins_age_verified'] == '1';
        
        // Apply filter to allow custom verification logic
        $is_verified = apply_filters('HotRocket_Age_Gate_shortcode_is_verified', $is_verified, $atts);
        
        if ($is_verified) {
            // User is verified, show content
            return do_shortcode($content);
        } else {
            // User is not verified, show restriction message
            $message = !empty($atts['message']) ? esc_html($atts['message']) : sprintf(
                __('This content is restricted to users %d years of age or older.', 'hotrocket-age-gate'),
                $age_limit
            );
            
            $button_text = __('Verify Your Age', 'hotrocket-age-gate');
            
            // Build restriction notice HTML
            ob_start();
            ?>
            <div class="hotrocket-age-gate-shortcode-restriction">
                <div class="hotrocket-age-gate-shortcode-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <p class="hotrocket-age-gate-shortcode-message"><?php echo $message; ?></p>
                <button class="hotrocket-age-gate-shortcode-button" onclick="hotrocketAgeGateShortcodeVerify(this, <?php echo esc_attr($age_limit); ?>)">
                    <?php echo $button_text; ?>
                </button>
            </div>
            <?php
            
            $html = ob_get_clean();
            
            // Apply filter to allow customization
            $html = apply_filters('HotRocket_Age_Gate_shortcode_restriction_html', $html, $atts, $content);
            
            // Add inline styles and scripts (only once)
            static $scripts_added = false;
            if (!$scripts_added) {
                $html .= $this->get_shortcode_styles();
                $html .= $this->get_shortcode_scripts();
                $scripts_added = true;
            }
            
            return $html;
        }
    }

    /**
     * Get inline styles for shortcode
     *
     * @return string
     */
    private function get_shortcode_styles() {
        $options = get_option('hotrocket_age_gate_options');
        $primary_color = !empty($options['primary_color']) ? esc_attr($options['primary_color']) : '#722f37';
        
        ob_start();
        ?>
        <style>
            .hotrocket-age-gate-shortcode-restriction {
                background: #f8f9fa;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                padding: 30px;
                text-align: center;
                margin: 20px 0;
            }
            .hotrocket-age-gate-shortcode-icon {
                width: 48px;
                height: 48px;
                margin: 0 auto 16px;
                color: <?php echo $primary_color; ?>;
            }
            .hotrocket-age-gate-shortcode-icon svg {
                width: 100%;
                height: 100%;
            }
            .hotrocket-age-gate-shortcode-message {
                font-size: 16px;
                color: #666;
                margin: 0 0 20px 0;
                line-height: 1.6;
            }
            .hotrocket-age-gate-shortcode-button {
                background-color: <?php echo $primary_color; ?>;
                color: #ffffff;
                border: none;
                padding: 12px 24px;
                font-size: 15px;
                font-weight: 600;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .hotrocket-age-gate-shortcode-button:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }
            .hotrocket-age-gate-shortcode-content {
                display: none;
            }
            .hotrocket-age-gate-shortcode-content.verified {
                display: block;
            }
        </style>
        <?php
        return ob_get_clean();
    }

    /**
     * Get inline scripts for shortcode
     *
     * @return string
     */
    private function get_shortcode_scripts() {
        ob_start();
        ?>
        <script>
        function hotrocketAgeGateShortcodeVerify(button, ageLimit) {
            // Check if main age gate cookie exists
            var isVerified = document.cookie.split(';').some(function(item) {
                return item.trim().indexOf('vins_age_verified=') === 0;
            });
            
            if (isVerified) {
                // Already verified, just reload
                location.reload();
            } else {
                // Trigger main age gate or show simple prompt
                if (typeof hotrocketAgeGate !== 'undefined' && jQuery('#hotrocketAgeGateOverlay').length) {
                    // Show main age gate
                    jQuery('#hotrocketAgeGateOverlay').fadeIn();
                } else {
                    // Simple confirmation
                    var confirmed = confirm('Are you ' + ageLimit + ' years of age or older?');
                    if (confirmed) {
                        // Set cookie and reload
                        var days = 30;
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        var expires = "; expires=" + date.toUTCString();
                        document.cookie = "vins_age_verified=1" + expires + "; path=/; SameSite=Lax";
                        location.reload();
                    }
                }
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }
}
