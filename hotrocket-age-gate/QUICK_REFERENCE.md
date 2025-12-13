# HotRocket Age Gate - Quick Reference Guide

## 🔖 Shortcodes

### Basic Shortcode
```
[age_gate]
This content is restricted to verified users only.
[/age_gate]
```

### Shortcode with Custom Age
```
[age_gate age="21"]
This premium content requires users to be 21 years or older.
[/age_gate]
```

### Shortcode with Custom Message
```
[age_gate message="You must be 21 to view this wine collection"]
Exclusive wine collection details...
[/age_gate]
```

### Shortcode with Both
```
[age_gate age="21" message="Premium content for 21+ only"]
Premium wine tasting notes and reviews...
[/age_gate]
```

### Alternative Shortcode Name
```
[age_restricted]
Same functionality as [age_gate]
[/age_restricted]
```

---

## 🎣 Developer Hooks

### Action Hooks

#### Before Modal
```php
add_action('hotrocket_age_gate_before_modal', function($options) {
    echo '<div class="custom-header">Welcome!</div>';
});
```

#### After Modal
```php
add_action('hotrocket_age_gate_after_modal', function($options) {
    echo '<script>console.log("Age gate loaded");</script>';
});
```

#### Before Buttons
```php
add_action('hotrocket_age_gate_before_buttons', function($options) {
    echo '<p class="custom-notice">Please verify responsibly</p>';
});
```

#### After Buttons
```php
add_action('hotrocket_age_gate_after_buttons', function($options) {
    echo '<p class="custom-footer">Additional info here</p>';
});
```

---

### Filter Hooks

#### Modify Bot List
```php
add_filter('hotrocket_age_gate_bot_list', function($bots) {
    // Add custom bot
    $bots[] = 'my-custom-bot';
    $bots[] = 'another-crawler';
    return $bots;
});
```

#### Control When Age Gate Shows
```php
add_filter('hotrocket_age_gate_should_show', function($should_show, $options) {
    // Don't show on about page
    if (is_page('about')) {
        return false;
    }
    
    // Don't show for specific user role
    if (current_user_can('administrator')) {
        return false;
    }
    
    // Don't show on weekends (example)
    if (date('N') >= 6) {
        return false;
    }
    
    return $should_show;
}, 10, 2);
```

#### Modify Modal HTML
```php
add_filter('hotrocket_age_gate_modal_html', function($html, $options) {
    // Add custom class to modal
    $html = str_replace('hotrocket-age-gate-modal', 'hotrocket-age-gate-modal custom-modal', $html);
    return $html;
}, 10, 2);
```

#### Modify Shortcode Verification
```php
add_filter('HotRocket_Age_Gate_shortcode_is_verified', function($is_verified, $atts) {
    // Custom verification logic
    // For example, check a custom cookie or session
    if (isset($_SESSION['custom_verified'])) {
        return true;
    }
    return $is_verified;
}, 10, 2);
```

#### Modify Shortcode Restriction HTML
```php
add_filter('HotRocket_Age_Gate_shortcode_restriction_html', function($html, $atts, $content) {
    // Customize the restriction message
    $custom_html = '<div class="my-custom-restriction">';
    $custom_html .= '<h3>Age Restricted Content</h3>';
    $custom_html .= '<p>Please verify your age to continue.</p>';
    $custom_html .= '</div>';
    return $custom_html;
}, 10, 3);
```

#### Add Custom Post Types to Meta Box
```php
add_filter('HotRocket_Age_Gate_metabox_post_types', function($post_types) {
    $post_types[] = 'product';  // WooCommerce products
    $post_types[] = 'event';    // Custom post type
    return $post_types;
});
```

---

### JavaScript Events

#### When User Verifies Age
```javascript
jQuery(document).on('hotrocket_age_gate_verified', function() {
    console.log('User verified their age!');
    
    // Track with Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'age_verified', {
            'event_category': 'engagement',
            'event_label': 'Age Gate'
        });
    }
    
    // Show welcome message
    alert('Welcome to our site!');
});
```

#### When User Denies Age
```javascript
jQuery(document).on('hotrocket_age_gate_denied', function() {
    console.log('User denied age verification');
    
    // Track with Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'age_denied', {
            'event_category': 'engagement',
            'event_label': 'Age Gate'
        });
    }
});
```

---

## 🎨 Custom CSS Examples

### Change Modal Border
```css
.hotrocket-age-gate-modal {
    border: 3px solid #722f37 !important;
    box-shadow: 0 0 50px rgba(114, 47, 55, 0.3) !important;
}
```

### Rounded Buttons
```css
.hotrocket-age-gate-btn {
    border-radius: 25px !important;
}
```

### Custom Animation
```css
.hotrocket-age-gate-modal {
    animation: customSlide 0.6s ease !important;
}

@keyframes customSlide {
    from {
        transform: translateY(-100px) scale(0.8);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}
```

### Change Overlay Blur
```css
.hotrocket-age-gate-overlay {
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
}
```

### Custom Logo Styling
```css
.hotrocket-custom-logo {
    border-radius: 50% !important;
    border: 3px solid #722f37 !important;
    padding: 10px !important;
}
```

### Date Input Styling
```css
.hotrocket-age-gate-date-field input {
    border-color: #722f37 !important;
    background: #f8f9fa !important;
}

.hotrocket-age-gate-date-field input:focus {
    box-shadow: 0 0 0 3px rgba(114, 47, 55, 0.2) !important;
}
```

---

## 📋 Common Use Cases

### Example 1: WooCommerce Product Age Restriction
```php
// In functions.php

// Add product to metabox
add_filter('HotRocket_Age_Gate_metabox_post_types', function($post_types) {
    $post_types[] = 'product';
    return $post_types;
});

// Set specific products to require age 21
// (Do this in the product editor, or programmatically:)
add_action('save_post_product', function($post_id) {
    // Example: All products in "Premium Wines" category require age 21
    if (has_term('premium-wines', 'product_cat', $post_id)) {
        update_post_meta($post_id, '_HotRocket_Age_Gate_require', '1');
        update_post_meta($post_id, '_HotRocket_Age_Gate_custom_age', 21);
    }
});
```

### Example 2: Skip Age Gate for Newsletter Subscribers
```php
add_filter('hotrocket_age_gate_should_show', function($should_show, $options) {
    // Check if user has newsletter cookie
    if (isset($_COOKIE['newsletter_subscriber'])) {
        return false;
    }
    return $should_show;
}, 10, 2);
```

### Example 3: Different Messages by Country
```php
add_action('hotrocket_age_gate_before_modal', function($options) {
    // Detect country (example using CloudFlare header)
    $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? $_SERVER['HTTP_CF_IPCOUNTRY'] : 'US';
    
    if ($country === 'US') {
        echo '<p class="country-notice">US visitors must be 21+</p>';
    } else {
        echo '<p class="country-notice">International visitors must be 18+</p>';
    }
});
```

### Example 4: Add Social Proof
```php
add_action('hotrocket_age_gate_after_buttons', function($options) {
    echo '<div class="social-proof">';
    echo '<p>✓ Trusted by 10,000+ wine enthusiasts</p>';
    echo '<p>✓ Secure & private verification</p>';
    echo '</div>';
});
```

### Example 5: Custom Verification with API
```php
add_filter('HotRocket_Age_Gate_shortcode_is_verified', function($is_verified, $atts) {
    // Check with external API
    if (isset($_SESSION['external_age_verified'])) {
        return $_SESSION['external_age_verified'];
    }
    
    // Fall back to cookie check
    return $is_verified;
}, 10, 2);
```

---

## 🔧 Troubleshooting Quick Fixes

### Age Gate Not Showing
```php
// Add to functions.php to debug
add_action('wp_footer', function() {
    if (current_user_can('administrator')) {
        echo '<!-- Age Gate Debug -->';
        echo '<!-- Cookie: ' . (isset($_COOKIE['vins_age_verified']) ? 'Set' : 'Not Set') . ' -->';
        echo '<!-- User Agent: ' . $_SERVER['HTTP_USER_AGENT'] . ' -->';
        echo '<!-- Is Bot: ' . (HotRocket_Age_Gate_Bot_Detector::is_bot() ? 'Yes' : 'No') . ' -->';
    }
});
```

### Clear All Age Verification Cookies
```javascript
// Run in browser console
document.cookie = 'vins_age_verified=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
location.reload();
```

### Force Show Age Gate (Testing)
```php
// Add to functions.php temporarily
add_filter('hotrocket_age_gate_should_show', function($should_show) {
    return true; // Always show
});
```

---

## 📞 Support

For more help:
- 📖 See `TUTORIAL.md` for detailed documentation
- 📖 See `README.md` for feature overview
- 📧 Email: support@hotrocket.dev

---

**Version:** 2.0.0  
**Last Updated:** December 2025

Made with care for wineries and age-restricted businesses 🍷
