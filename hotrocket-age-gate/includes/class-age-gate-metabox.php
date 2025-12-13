<?php
/**
 * Meta Box for Age Gate
 * Adds per-post/page age restriction settings
 */

if (!defined('ABSPATH')) {
    exit;
}

class HotRocket_Age_Gate_Metabox {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box'));
    }

    /**
     * Add meta box to posts and pages
     */
    public function add_meta_box() {
        $post_types = apply_filters('HotRocket_Age_Gate_metabox_post_types', array('post', 'page', 'product'));
        
        add_meta_box(
            'HotRocket_Age_Gate_metabox',
            __('Age Verification Settings', 'hotrocket-age-gate'),
            array($this, 'render_meta_box'),
            $post_types,
            'side',
            'default'
        );
    }

    /**
     * Render meta box content
     *
     * @param WP_Post $post Current post object
     */
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('HotRocket_Age_Gate_metabox', 'HotRocket_Age_Gate_metabox_nonce');
        
        // Get current values
        $require_verification = get_post_meta($post->ID, '_HotRocket_Age_Gate_require', true);
        $custom_age = get_post_meta($post->ID, '_HotRocket_Age_Gate_custom_age', true);
        $exclude_from_all = get_post_meta($post->ID, '_HotRocket_Age_Gate_exclude', true);
        
        // Get global settings
        $options = get_option('hotrocket_age_gate_options');
        $global_age = !empty($options['age_limit']) ? intval($options['age_limit']) : 18;
        $restriction_mode = !empty($options['restriction_mode']) ? $options['restriction_mode'] : 'entire_site';
        
        ?>
        <div class="hotrocket-age-gate-metabox">
            <?php if ($restriction_mode === 'selected_content'): ?>
                <p>
                    <label>
                        <input type="checkbox" name="HotRocket_Age_Gate_require" value="1" <?php checked($require_verification, '1'); ?>>
                        <strong><?php _e('Require age verification for this content', 'hotrocket-age-gate'); ?></strong>
                    </label>
                </p>
            <?php else: ?>
                <p>
                    <label>
                        <input type="checkbox" name="HotRocket_Age_Gate_exclude" value="1" <?php checked($exclude_from_all, '1'); ?>>
                        <strong><?php _e('Exclude from age verification', 'hotrocket-age-gate'); ?></strong>
                    </label>
                    <br>
                    <small class="description"><?php _e('Check this to skip age verification for this content even though "All Content" mode is enabled.', 'hotrocket-age-gate'); ?></small>
                </p>
            <?php endif; ?>
            
            <hr style="margin: 15px 0;">
            
            <p>
                <label for="HotRocket_Age_Gate_custom_age">
                    <strong><?php _e('Custom Age Limit', 'hotrocket-age-gate'); ?></strong>
                </label>
                <br>
                <input type="number" 
                       id="HotRocket_Age_Gate_custom_age" 
                       name="HotRocket_Age_Gate_custom_age" 
                       value="<?php echo esc_attr($custom_age); ?>" 
                       min="1" 
                       max="100" 
                       class="small-text"
                       placeholder="<?php echo esc_attr($global_age); ?>">
                <br>
                <small class="description">
                    <?php printf(__('Leave empty to use global age limit (%d years). Set a custom age for this specific content.', 'hotrocket-age-gate'), $global_age); ?>
                </small>
            </p>
            
            <hr style="margin: 15px 0;">
            
            <p class="description">
                <strong><?php _e('Current Mode:', 'hotrocket-age-gate'); ?></strong>
                <?php 
                if ($restriction_mode === 'entire_site') {
                    _e('Entire Site', 'hotrocket-age-gate');
                } else {
                    _e('Selected Content Only', 'hotrocket-age-gate');
                }
                ?>
                <br>
                <a href="<?php echo admin_url('options-general.php?page=hotrocket-age-gate'); ?>">
                    <?php _e('Change in settings', 'hotrocket-age-gate'); ?>
                </a>
            </p>
        </div>
        
        <style>
            .hotrocket-age-gate-metabox {
                padding: 5px 0;
            }
            .hotrocket-age-gate-metabox label {
                display: block;
                margin-bottom: 5px;
            }
            .hotrocket-age-gate-metabox input[type="checkbox"] {
                margin-right: 5px;
            }
        </style>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_box($post_id) {
        // Check if nonce is set
        if (!isset($_POST['HotRocket_Age_Gate_metabox_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['HotRocket_Age_Gate_metabox_nonce'], 'HotRocket_Age_Gate_metabox')) {
            return;
        }
        
        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save require verification checkbox
        if (isset($_POST['HotRocket_Age_Gate_require'])) {
            update_post_meta($post_id, '_HotRocket_Age_Gate_require', '1');
        } else {
            delete_post_meta($post_id, '_HotRocket_Age_Gate_require');
        }
        
        // Save exclude checkbox
        if (isset($_POST['HotRocket_Age_Gate_exclude'])) {
            update_post_meta($post_id, '_HotRocket_Age_Gate_exclude', '1');
        } else {
            delete_post_meta($post_id, '_HotRocket_Age_Gate_exclude');
        }
        
        // Save custom age
        if (isset($_POST['HotRocket_Age_Gate_custom_age']) && !empty($_POST['HotRocket_Age_Gate_custom_age'])) {
            $custom_age = intval($_POST['HotRocket_Age_Gate_custom_age']);
            if ($custom_age > 0 && $custom_age <= 100) {
                update_post_meta($post_id, '_HotRocket_Age_Gate_custom_age', $custom_age);
            }
        } else {
            delete_post_meta($post_id, '_HotRocket_Age_Gate_custom_age');
        }
    }

    /**
     * Check if current post requires age verification
     *
     * @param int $post_id Post ID
     * @return bool
     */
    public static function requires_verification($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        if (!$post_id) {
            return false;
        }
        
        $options = get_option('hotrocket_age_gate_options');
        $restriction_mode = !empty($options['restriction_mode']) ? $options['restriction_mode'] : 'entire_site';
        
        if ($restriction_mode === 'entire_site') {
            // Check if excluded
            $excluded = get_post_meta($post_id, '_HotRocket_Age_Gate_exclude', true);
            return $excluded !== '1';
        } else {
            // Check if specifically required
            $required = get_post_meta($post_id, '_HotRocket_Age_Gate_require', true);
            return $required === '1';
        }
    }

    /**
     * Get custom age for post
     *
     * @param int $post_id Post ID
     * @return int|null Custom age or null if not set
     */
    public static function get_custom_age($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        if (!$post_id) {
            return null;
        }
        
        $custom_age = get_post_meta($post_id, '_HotRocket_Age_Gate_custom_age', true);
        
        if (!empty($custom_age)) {
            return intval($custom_age);
        }
        
        return null;
    }
}
