<?php
/**
 * Admin settings page for Age Gate
 */

if (!defined('ABSPATH')) {
    exit;
}

class HotRocket_Age_Gate_Admin {

    public static function add_admin_menu() {
        add_options_page(
            'Vin\'s Age Gate Settings',
            'Age Gate',
            'manage_options',
            'hotrocket-age-gate',
            array(__CLASS__, 'render_settings_page')
        );
    }

    public static function register_settings() {
        register_setting('hotrocket_age_gate_settings', 'hotrocket_age_gate_options', array(__CLASS__, 'sanitize_options'));

        // General Settings Section
        add_settings_section(
            'hotrocket_age_gate_general',
            'General Settings',
            array(__CLASS__, 'general_section_callback'),
            'hotrocket-age-gate'
        );

        // Content Settings Section
        add_settings_section(
            'hotrocket_age_gate_content',
            'Content Settings',
            array(__CLASS__, 'content_section_callback'),
            'hotrocket-age-gate'
        );

        // Design Settings Section
        add_settings_section(
            'hotrocket_age_gate_design',
            'Design & Appearance',
            array(__CLASS__, 'design_section_callback'),
            'hotrocket-age-gate'
        );

        // General Settings Fields
        add_settings_field('enabled', 'Enable Age Gate', array(__CLASS__, 'enabled_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_general');
        add_settings_field('age_limit', 'Age Limit', array(__CLASS__, 'age_limit_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_general');
        add_settings_field('cookie_duration', 'Cookie Duration (Days)', array(__CLASS__, 'cookie_duration_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_general');
        add_settings_field('redirect_url', 'Redirect URL (on "No")', array(__CLASS__, 'redirect_url_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_general');
        add_settings_field('show_remember', 'Show "Remember Me"', array(__CLASS__, 'show_remember_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_general');

        // Content Settings Fields
        add_settings_field('welcome_title', 'Welcome Title', array(__CLASS__, 'welcome_title_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_content');
        add_settings_field('welcome_message', 'Welcome Message', array(__CLASS__, 'welcome_message_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_content');
        add_settings_field('button_yes_text', 'Yes Button Text', array(__CLASS__, 'button_yes_text_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_content');
        add_settings_field('button_no_text', 'No Button Text', array(__CLASS__, 'button_no_text_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_content');

        // Design Settings Fields
        add_settings_field('custom_logo', 'Custom Logo/Icon', array(__CLASS__, 'custom_logo_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('logo_size', 'Logo/Icon Size', array(__CLASS__, 'logo_size_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('popup_size', 'Popup Window Size', array(__CLASS__, 'popup_size_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('overlay_opacity', 'Overlay Transparency', array(__CLASS__, 'overlay_opacity_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('overlay_color', 'Overlay Color', array(__CLASS__, 'overlay_color_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('popup_bg_color', 'Popup Background Color', array(__CLASS__, 'popup_bg_color_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('primary_color', 'Primary Button Color', array(__CLASS__, 'primary_color_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('text_color', 'Text Color', array(__CLASS__, 'text_color_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
        add_settings_field('button_text_color', 'Button Text Color', array(__CLASS__, 'button_text_color_callback'), 'hotrocket-age-gate', 'hotrocket_age_gate_design');
    }

    public static function sanitize_options($input) {
        $sanitized = array();

        $sanitized['enabled'] = !empty($input['enabled']) ? 1 : 0;
        $sanitized['age_limit'] = !empty($input['age_limit']) ? intval($input['age_limit']) : 18;
        $sanitized['welcome_title'] = !empty($input['welcome_title']) ? sanitize_text_field($input['welcome_title']) : '';
        $sanitized['welcome_message'] = !empty($input['welcome_message']) ? sanitize_textarea_field($input['welcome_message']) : '';
        $sanitized['button_yes_text'] = !empty($input['button_yes_text']) ? sanitize_text_field($input['button_yes_text']) : '';
        $sanitized['button_no_text'] = !empty($input['button_no_text']) ? sanitize_text_field($input['button_no_text']) : '';
        $sanitized['cookie_duration'] = !empty($input['cookie_duration']) ? intval($input['cookie_duration']) : 30;
        $sanitized['redirect_url'] = !empty($input['redirect_url']) ? esc_url_raw($input['redirect_url']) : '';
        $sanitized['show_remember'] = !empty($input['show_remember']) ? 1 : 0;
        $sanitized['overlay_opacity'] = !empty($input['overlay_opacity']) ? intval($input['overlay_opacity']) : 95;
        $sanitized['overlay_color'] = !empty($input['overlay_color']) ? sanitize_hex_color($input['overlay_color']) : '#000000';
        $sanitized['popup_bg_color'] = !empty($input['popup_bg_color']) ? sanitize_hex_color($input['popup_bg_color']) : '#ffffff';
        $sanitized['primary_color'] = !empty($input['primary_color']) ? sanitize_hex_color($input['primary_color']) : '#722f37';
        $sanitized['text_color'] = !empty($input['text_color']) ? sanitize_hex_color($input['text_color']) : '#333333';
        $sanitized['button_text_color'] = !empty($input['button_text_color']) ? sanitize_hex_color($input['button_text_color']) : '#ffffff';
        $sanitized['custom_logo'] = !empty($input['custom_logo']) ? esc_url_raw($input['custom_logo']) : '';

        // Validate logo size - only allow small, medium, large
        $allowed_sizes = array('small', 'medium', 'large');
        $sanitized['logo_size'] = !empty($input['logo_size']) && in_array($input['logo_size'], $allowed_sizes) ? $input['logo_size'] : 'medium';

        // Validate popup size - only allow small, medium, large
        $sanitized['popup_size'] = !empty($input['popup_size']) && in_array($input['popup_size'], $allowed_sizes) ? $input['popup_size'] : 'large';

        return $sanitized;
    }

    // Section Callbacks
    public static function general_section_callback() {
        echo '<p>Configure the basic behavior of the age verification gate.</p>';
    }

    public static function content_section_callback() {
        echo '<p>Customize the text and messaging displayed to users.</p>';
    }

    public static function design_section_callback() {
        echo '<p>Customize the visual appearance and colors of the age gate popup.</p>';
    }

    // Field Callbacks
    public static function enabled_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $checked = !empty($options['enabled']) ? 'checked' : '';
        echo '<label><input type="checkbox" name="hotrocket_age_gate_options[enabled]" value="1" ' . $checked . '> Enable age verification popup</label>';
    }

    public static function age_limit_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['age_limit']) ? intval($options['age_limit']) : 18;
        echo '<input type="number" name="hotrocket_age_gate_options[age_limit]" value="' . esc_attr($value) . '" min="1" max="100" class="small-text">';
        echo '<p class="description">Minimum age required to enter the site (e.g., 18, 21)</p>';
    }

    public static function welcome_title_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['welcome_title']) ? esc_attr($options['welcome_title']) : 'Welcome to our site';
        echo '<input type="text" name="hotrocket_age_gate_options[welcome_title]" value="' . $value . '" class="regular-text">';
    }

    public static function welcome_message_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['welcome_message']) ? esc_textarea($options['welcome_message']) : 'Please confirm that you are over 18 years old to enter this site.';
        echo '<textarea name="hotrocket_age_gate_options[welcome_message]" rows="3" class="large-text">' . $value . '</textarea>';
    }

    public static function button_yes_text_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['button_yes_text']) ? esc_attr($options['button_yes_text']) : 'Yes, I\'m 18+';
        echo '<input type="text" name="hotrocket_age_gate_options[button_yes_text]" value="' . $value . '" class="regular-text">';
    }

    public static function button_no_text_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['button_no_text']) ? esc_attr($options['button_no_text']) : 'No, Exit';
        echo '<input type="text" name="hotrocket_age_gate_options[button_no_text]" value="' . $value . '" class="regular-text">';
    }

    public static function cookie_duration_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['cookie_duration']) ? intval($options['cookie_duration']) : 30;
        echo '<input type="number" name="hotrocket_age_gate_options[cookie_duration]" value="' . esc_attr($value) . '" min="1" max="365" class="small-text">';
        echo '<p class="description">How long to remember the user\'s verification (1-365 days)</p>';
    }

    public static function redirect_url_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['redirect_url']) ? esc_url($options['redirect_url']) : 'https://www.google.com';
        echo '<input type="url" name="hotrocket_age_gate_options[redirect_url]" value="' . $value . '" class="regular-text">';
        echo '<p class="description">Where to redirect users who click "No"</p>';
    }

    public static function show_remember_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $checked = !empty($options['show_remember']) ? 'checked' : '';
        echo '<label><input type="checkbox" name="hotrocket_age_gate_options[show_remember]" value="1" ' . $checked . '> Show "Remember me" checkbox</label>';
    }

    public static function custom_logo_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $logo_url = !empty($options['custom_logo']) ? esc_url($options['custom_logo']) : '';
        ?>
        <div class="hotrocket-logo-upload-wrapper">
            <input type="hidden" name="hotrocket_age_gate_options[custom_logo]" id="vins_custom_logo" value="<?php echo $logo_url; ?>">
            <div class="hotrocket-logo-preview">
                <?php if ($logo_url): ?>
                    <img src="<?php echo $logo_url; ?>" alt="Custom Logo" style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                <?php else: ?>
                    <div class="hotrocket-logo-placeholder" style="width: 150px; height: 150px; border: 2px dashed #ddd; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; border-radius: 8px;">
                        <span style="color: #999;">No logo selected</span>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="button button-secondary hotrocket-upload-logo-btn">
                <?php echo $logo_url ? 'Change Logo' : 'Upload Logo'; ?>
            </button>
            <?php if ($logo_url): ?>
                <button type="button" class="button button-link-delete hotrocket-remove-logo-btn" style="margin-left: 10px;">Remove Logo</button>
            <?php endif; ?>
            <p class="description">Upload a custom logo or icon to display at the top of the age gate popup. Leave empty to use the default wine glass icon. Recommended size: 150x150px or smaller.</p>
        </div>
        <?php
    }

    public static function logo_size_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $current_size = !empty($options['logo_size']) ? esc_attr($options['logo_size']) : 'medium';
        ?>
        <select name="hotrocket_age_gate_options[logo_size]" class="regular-text">
            <option value="small" <?php selected($current_size, 'small'); ?>>Small (48px)</option>
            <option value="medium" <?php selected($current_size, 'medium'); ?>>Medium (64px)</option>
            <option value="large" <?php selected($current_size, 'large'); ?>>Large (96px)</option>
        </select>
        <p class="description">Choose the display size for the logo/icon in the popup</p>
        <?php
    }

    public static function popup_size_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $current_size = !empty($options['popup_size']) ? esc_attr($options['popup_size']) : 'large';
        ?>
        <select name="hotrocket_age_gate_options[popup_size]" class="regular-text">
            <option value="small" <?php selected($current_size, 'small'); ?>>Small (320px)</option>
            <option value="medium" <?php selected($current_size, 'medium'); ?>>Medium (400px)</option>
            <option value="large" <?php selected($current_size, 'large'); ?>>Large (500px)</option>
        </select>
        <p class="description">Choose the size of the popup window itself</p>
        <?php
    }

    public static function overlay_opacity_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['overlay_opacity']) ? intval($options['overlay_opacity']) : 95;
        echo '<input type="range" name="hotrocket_age_gate_options[overlay_opacity]" value="' . esc_attr($value) . '" min="0" max="100" step="5" class="hotrocket-range-slider" oninput="this.nextElementSibling.textContent = this.value + \'%\'">';
        echo '<span class="hotrocket-range-value">' . $value . '%</span>';
        echo '<p class="description">0% = fully transparent, 100% = fully opaque</p>';
    }

    public static function overlay_color_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['overlay_color']) ? esc_attr($options['overlay_color']) : '#000000';
        echo '<input type="color" name="hotrocket_age_gate_options[overlay_color]" value="' . $value . '" class="hotrocket-color-picker">';
        echo '<input type="text" value="' . $value . '" class="regular-text hotrocket-color-text" readonly>';
        echo '<p class="description">Background overlay color behind the popup</p>';
    }

    public static function popup_bg_color_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['popup_bg_color']) ? esc_attr($options['popup_bg_color']) : '#ffffff';
        echo '<input type="color" name="hotrocket_age_gate_options[popup_bg_color]" value="' . $value . '" class="hotrocket-color-picker">';
        echo '<input type="text" value="' . $value . '" class="regular-text hotrocket-color-text" readonly>';
        echo '<p class="description">Background color of the popup modal</p>';
    }

    public static function primary_color_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['primary_color']) ? esc_attr($options['primary_color']) : '#ff6b35';
        echo '<input type="color" name="hotrocket_age_gate_options[primary_color]" value="' . $value . '" class="hotrocket-color-picker">';
        echo '<input type="text" value="' . $value . '" class="regular-text hotrocket-color-text" readonly>';
        echo '<p class="description">Color of the "Yes" button</p>';
    }

    public static function text_color_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['text_color']) ? esc_attr($options['text_color']) : '#333333';
        echo '<input type="color" name="hotrocket_age_gate_options[text_color]" value="' . $value . '" class="hotrocket-color-picker">';
        echo '<input type="text" value="' . $value . '" class="regular-text hotrocket-color-text" readonly>';
        echo '<p class="description">Main text color in the popup</p>';
    }

    public static function button_text_color_callback() {
        $options = get_option('hotrocket_age_gate_options');
        $value = !empty($options['button_text_color']) ? esc_attr($options['button_text_color']) : '#ffffff';
        echo '<input type="color" name="hotrocket_age_gate_options[button_text_color]" value="' . $value . '" class="hotrocket-color-picker">';
        echo '<input type="text" value="' . $value . '" class="regular-text hotrocket-color-text" readonly>';
        echo '<p class="description">Text color on the "Yes" button</p>';
    }

    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Enqueue WordPress media uploader
        wp_enqueue_media();

        // Add custom admin styles
        self::add_admin_styles();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="hotrocket-age-gate-admin-header">
                <p class="description">Configure your age verification popup. Customize the appearance, messaging, and behavior to match your brand.</p>
            </div>

            <?php settings_errors('hotrocket_age_gate_options'); ?>

            <form action="options.php" method="post">
                <?php
                settings_fields('hotrocket_age_gate_settings');
                do_settings_sections('hotrocket-age-gate');
                submit_button('Save Settings');
                ?>
            </form>

            <div class="hotrocket-age-gate-preview-notice">
                <h3>Preview Your Changes</h3>
                <p>To see your changes in action, clear your browser cookies for this site or open the site in an incognito/private window.</p>
                <button type="button" class="button button-secondary" onclick="document.cookie = 'vins_age_verified=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; alert('Cookie cleared! Reload the frontend to see the age gate.');">Clear Age Verification Cookie</button>
            </div>
        </div>
        <?php
    }

    private static function add_admin_styles() {
        ?>
        <style>
            .hotrocket-age-gate-admin-header {
                background: #fff;
                border-left: 4px solid #722f37;
                padding: 15px 20px;
                margin: 20px 0;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .hotrocket-age-gate-preview-notice {
                background: #f0f6fc;
                border: 1px solid #0969da;
                border-radius: 6px;
                padding: 20px;
                margin: 30px 0;
            }

            .hotrocket-age-gate-preview-notice h3 {
                margin-top: 0;
                color: #0969da;
            }

            .hotrocket-range-slider {
                width: 300px;
                vertical-align: middle;
            }

            .hotrocket-range-value {
                display: inline-block;
                margin-left: 15px;
                font-weight: 600;
                min-width: 45px;
            }

            .hotrocket-color-picker {
                vertical-align: middle;
                margin-right: 10px;
                height: 40px;
                width: 60px;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
            }

            .hotrocket-color-text {
                vertical-align: middle;
                width: 100px !important;
            }

            .form-table th {
                width: 250px;
            }

            h2 {
                border-bottom: 2px solid #722f37;
                padding-bottom: 10px;
                color: #722f37;
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                // Update color text field when color picker changes
                $('.hotrocket-color-picker').on('change', function() {
                    $(this).next('.hotrocket-color-text').val($(this).val());
                });

                // WordPress Media Uploader for Logo
                var mediaUploader;

                $('.hotrocket-upload-logo-btn').on('click', function(e) {
                    e.preventDefault();

                    // If the uploader object has already been created, reopen the dialog
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    // Create the media uploader
                    mediaUploader = wp.media({
                        title: 'Choose Logo',
                        button: {
                            text: 'Use This Logo'
                        },
                        multiple: false,
                        library: {
                            type: 'image'
                        }
                    });

                    // When an image is selected, run a callback
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();

                        // Set the value of the hidden input
                        $('#vins_custom_logo').val(attachment.url);

                        // Update the preview
                        $('.hotrocket-logo-preview').html('<img src="' + attachment.url + '" alt="Custom Logo" style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">');

                        // Update button text
                        $('.hotrocket-upload-logo-btn').text('Change Logo');

                        // Add remove button if it doesn't exist
                        if (!$('.hotrocket-remove-logo-btn').length) {
                            $('.hotrocket-upload-logo-btn').after('<button type="button" class="button button-link-delete hotrocket-remove-logo-btn" style="margin-left: 10px;">Remove Logo</button>');
                        }
                    });

                    // Open the uploader dialog
                    mediaUploader.open();
                });

                // Remove logo button
                $(document).on('click', '.hotrocket-remove-logo-btn', function(e) {
                    e.preventDefault();

                    // Clear the hidden input
                    $('#vins_custom_logo').val('');

                    // Reset the preview
                    $('.hotrocket-logo-preview').html('<div class="hotrocket-logo-placeholder" style="width: 150px; height: 150px; border: 2px dashed #ddd; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; border-radius: 8px;"><span style="color: #999;">No logo selected</span></div>');

                    // Update button text
                    $('.hotrocket-upload-logo-btn').text('Upload Logo');

                    // Remove the remove button
                    $('.hotrocket-remove-logo-btn').remove();
                });
            });
        </script>
        <?php
    }
}
