# HotRocket Age Gate - Complete Tutorial & Documentation

## Table of Contents
1. Introduction
2. Installation
3. Basic Setup
4. Feature Overview
5. Advanced Configuration
6. Customization
7. Troubleshooting
8. FAQ
9. Developer Hooks

---

## 1. Introduction

**HotRocket Age Gate** is a powerful, feature-rich WordPress plugin designed to verify the age of visitors before they can access your website content. Perfect for wineries, breweries, tobacco shops, adult content sites, and any business that needs to comply with age restriction laws.

### Key Features:
- ✅ **SEO-Friendly** - Automatically detects and skips bots/crawlers
- ✅ **Flexible Restrictions** - Restrict entire site or selected content
- ✅ **Multiple Verification Methods** - Simple Yes/No or Date of Birth input
- ✅ **Customizable Design** - Match your brand perfectly
- ✅ **Mobile Responsive** - Works flawlessly on all devices
- ✅ **Multilingual Ready** - Compatible with WPML, Polylang, WP Multilang
- ✅ **Developer Friendly** - Extensive hooks for customization
- ✅ **Per-Content Settings** - Different ages for different content
- ✅ **Shortcode Support** - Restrict specific sections of content

---

## 2. Installation

### Method 1: WordPress Admin Upload

1. Download the `hotrocket-age-gate.zip` file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins → Add New**
4. Click **Upload Plugin** button at the top
5. Click **Choose File** and select the `hotrocket-age-gate.zip` file
6. Click **Install Now**
7. After installation, click **Activate Plugin**

### Method 2: FTP Upload

1. Extract the `hotrocket-age-gate.zip` file on your computer
2. Connect to your website via FTP
3. Navigate to `/wp-content/plugins/`
4. Upload the entire `hotrocket-age-gate` folder
5. Go to WordPress admin → **Plugins**
6. Find "HotRocket Age Gate" and click **Activate**

---

## 3. Basic Setup

### Initial Configuration

After activation, configure your age gate:

1. Go to **Settings → Age Gate** in WordPress admin
2. Check **Enable Age Gate** to turn it on
3. Set your **Age Limit** (default: 18)
4. Configure **Cookie Duration** (how long to remember verified users)
5. Set **Redirect URL** (where to send users who click "No")
6. Click **Save Settings**

### Testing Your Age Gate

To see the age gate in action:

**Option 1: Clear Cookies**
- Click the "Clear Age Verification Cookie" button in the admin
- Visit your website in a new tab

**Option 2: Incognito Mode**
- Open your website in an incognito/private browser window
- The age gate will appear on every visit

---

## 4. Feature Overview

### 4.1 SEO-Friendly Bot Detection

The plugin automatically detects search engine crawlers and bots, allowing them to access your site without age verification. This ensures your site remains indexed by search engines.

**Included Bots:**
- Googlebot, Bingbot, Yahoo Slurp
- Facebook, Twitter, LinkedIn crawlers
- SEO tools (Ahrefs, SEMrush, Moz)
- And 50+ more common bots

**Custom Bot User Agents:**
Go to **Settings → Age Gate → Advanced Settings** and add custom bot user agents (comma or line-separated) in the "Custom Bot User Agents" field.

### 4.2 Restriction Modes

**Entire Site Mode (Default):**
- Age gate appears on all pages
- You can exclude specific pages/posts

**Selected Content Mode:**
- Age gate only appears on content you specifically mark
- Useful if only certain products/pages need age verification

To change modes:
1. Go to **Settings → Age Gate → Advanced Settings**
2. Select your preferred **Restriction Mode**
3. Save settings

### 4.3 Per-Content Settings

When editing a post or page, look for the **Age Verification Settings** meta box in the sidebar.

**Options Available:**
- **Require/Exclude Verification** - Depends on your restriction mode
- **Custom Age Limit** - Override global age for this specific content
- **Example:** Set global age to 18, but require 21 for specific products

### 4.4 Verification Methods

**Simple Yes/No (Default):**
- Users click "Yes, I'm 18+" or "No, Exit"
- Quick and easy for users
- Best for most use cases

**Date of Birth Input:**
- Users enter their actual date of birth
- Plugin calculates age automatically
- More accurate verification
- Customizable date format (DD/MM/YYYY, MM/DD/YYYY, YYYY/MM/DD)

To change verification method:
1. Go to **Settings → Age Gate → Advanced Settings**
2. Select **Verification Method**
3. Choose **Date Format** if using date input
4. Save settings

### 4.5 Shortcode for In-Content Restrictions

Restrict specific sections of content using shortcodes:

```
[age_gate]
This content is only visible to verified users.
[/age_gate]
```

**With Custom Age:**
```
[age_gate age="21"]
This content requires users to be 21+
[/age_gate]
```

**With Custom Message:**
```
[age_gate message="You must be 21 to view this premium content"]
Premium wine collection details here...
[/age_gate]
```

### 4.6 Skip Logged-In Users

Automatically skip age verification for logged-in WordPress users:

1. Go to **Settings → Age Gate → Advanced Settings**
2. Check **Skip Logged-In Users**
3. Save settings

This is useful if you have a membership site where users have already verified their age during registration.

### 4.7 Legal Note

Add legal information or disclaimers to the bottom of your age gate:

1. Go to **Settings → Age Gate → Content Settings**
2. Enter text in the **Legal Note** field
3. HTML is allowed for formatting
4. Save settings

**Example:**
```
By entering this site, you agree to our <a href="/terms">Terms of Service</a> 
and confirm you are of legal drinking age in your jurisdiction.
```

---

## 5. Advanced Configuration

### 5.1 Customizing Colors & Design

**Design Settings Location:** Settings → Age Gate → Design & Appearance

**Customizable Elements:**
- **Custom Logo** - Upload your own logo (replaces wine glass icon)
- **Logo Size** - Small (48px), Medium (64px), Large (96px)
- **Popup Size** - Small (350px), Medium (420px), Large (500px)
- **Overlay Color** - Background color behind popup
- **Overlay Transparency** - 0-100% opacity
- **Popup Background** - Modal background color
- **Primary Button Color** - "Yes" button color
- **Text Color** - Main text color
- **Button Text Color** - Text color on "Yes" button

**Design Tips:**
- Use your brand colors for consistency
- High contrast (dark overlay + light popup) for visibility
- Test on mobile devices
- Consider accessibility (readable text colors)

### 5.2 Content Customization

**Customizable Text:**
- Welcome Title
- Welcome Message
- Yes Button Text
- No Button Text
- Legal Note

**Multilingual Support:**
All text is translatable using:
- WPML
- Polylang (2.3+)
- WP Multilang
- .po/.mo translation files

### 5.3 Caching Compatibility

If you're using a caching plugin and experiencing issues:

1. Go to **Settings → Age Gate → Advanced Settings**
2. Check **Disable Caching**
3. Save settings

This adds no-cache headers and cache-busting to prevent cached age gates.

**Alternative:** Exclude age gate from your caching plugin's settings.

---

## 6. Customization

### 6.1 Using Hooks (For Developers)

**Action Hooks:**

```php
// Before age gate modal renders
add_action('hotrocket_age_gate_before_modal', function($options) {
    echo '<div class="custom-header">Custom content here</div>';
});

// After age gate modal renders
add_action('hotrocket_age_gate_after_modal', function($options) {
    echo '<script>console.log("Age gate loaded");</script>';
});

// Before buttons
add_action('hotrocket_age_gate_before_buttons', function($options) {
    echo '<p class="custom-notice">Please verify responsibly</p>';
});

// After buttons
add_action('hotrocket_age_gate_after_buttons', function($options) {
    // Add custom content
});

// When user verifies age (JavaScript)
jQuery(document).on('hotrocket_age_gate_verified', function() {
    console.log('User verified!');
});

// When user denies age (JavaScript)
jQuery(document).on('hotrocket_age_gate_denied', function() {
    console.log('User denied');
});
```

**Filter Hooks:**

```php
// Modify bot list
add_filter('hotrocket_age_gate_bot_list', function($bots) {
    $bots[] = 'my-custom-bot';
    return $bots;
});

// Modify whether age gate should show
add_filter('hotrocket_age_gate_should_show', function($should_show, $options) {
    // Custom logic
    if (is_page('about')) {
        return false; // Don't show on about page
    }
    return $should_show;
}, 10, 2);

// Modify modal HTML
add_filter('hotrocket_age_gate_modal_html', function($html, $options) {
    // Modify HTML before output
    return $html;
}, 10, 2);

// Modify shortcode restriction HTML
add_filter('HotRocket_Age_Gate_shortcode_restriction_html', function($html, $atts, $content) {
    // Custom restriction message
    return $html;
}, 10, 3);
```

### 6.2 Custom CSS

Add custom CSS in **Appearance → Customize → Additional CSS**:

```css
/* Customize modal appearance */
.hotrocket-age-gate-modal {
    border: 3px solid #722f37 !important;
}

/* Customize buttons */
.hotrocket-age-gate-btn-yes {
    border-radius: 25px !important;
}

/* Add custom animation */
.hotrocket-age-gate-modal {
    animation: customSlide 0.5s ease !important;
}

@keyframes customSlide {
    from {
        transform: translateY(-100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
```

---

## 7. Troubleshooting

### Age Gate Not Appearing

**Check:**
1. Is the plugin activated?
2. Is "Enable Age Gate" checked in settings?
3. Have you already verified (clear cookies)?
4. Are you logged in with "Skip Logged-In Users" enabled?
5. Is the current page excluded?

### Age Gate Appears on Every Page Load

**Solutions:**
1. Check if cookies are enabled in browser
2. Disable caching plugins temporarily
3. Enable "Disable Caching" in plugin settings
4. Check for cookie-blocking browser extensions

### Design Issues

**Solutions:**
1. Clear browser cache
2. Check for theme CSS conflicts
3. Try different popup/logo sizes
4. Disable other popup plugins temporarily

### Bot Detection Not Working

**Solutions:**
1. Check user agent strings in server logs
2. Add custom user agents in settings
3. Test with different bot simulators
4. Contact support with bot details

---

## 8. FAQ

**Q: Will this affect my SEO?**
A: No! The plugin automatically detects search engine bots and allows them to crawl your site without age verification.

**Q: Can I use this with WooCommerce?**
A: Yes! The plugin works with WooCommerce. You can restrict the entire site or specific products.

**Q: Is it GDPR compliant?**
A: The plugin only uses a simple cookie to remember verification. No personal data is collected. You should still mention cookies in your privacy policy.

**Q: Can I have different age limits for different pages?**
A: Yes! Use the custom age field in the page/post editor sidebar.

**Q: Does it work with caching plugins?**
A: Yes, but you may need to enable "Disable Caching" in advanced settings or exclude the age gate from your cache.

**Q: Can I translate the plugin?**
A: Yes! The plugin is fully translatable and compatible with WPML, Polylang, and WP Multilang.

**Q: How do I test changes?**
A: Use the "Clear Age Verification Cookie" button in admin, or use incognito/private browsing mode.

**Q: Can I customize the appearance?**
A: Absolutely! Use the built-in design settings or add custom CSS.

---

## 9. Developer Hooks

### Complete Hook Reference

**Action Hooks:**
- `hotrocket_age_gate_before_modal` - Before modal HTML
- `hotrocket_age_gate_after_modal` - After modal HTML
- `hotrocket_age_gate_before_buttons` - Before buttons
- `hotrocket_age_gate_after_buttons` - After buttons

**Filter Hooks:**
- `hotrocket_age_gate_options` - Modify all options
- `hotrocket_age_gate_modal_html` - Modify modal HTML
- `hotrocket_age_gate_bot_list` - Modify bot detection list
- `hotrocket_age_gate_should_show` - Control when age gate shows
- `HotRocket_Age_Gate_shortcode_is_verified` - Custom verification logic for shortcodes
- `HotRocket_Age_Gate_shortcode_restriction_html` - Modify shortcode restriction message
- `HotRocket_Age_Gate_metabox_post_types` - Add custom post types to metabox

**JavaScript Events:**
- `hotrocket_age_gate_verified` - Triggered when user verifies
- `hotrocket_age_gate_denied` - Triggered when user denies

---

## Support & Updates

For support, feature requests, or bug reports:
- Email: support@hotrocket.dev
- Documentation: https://hotrocket.dev/age-gate-docs

**Version:** 2.0.0
**Last Updated:** December 2025

---

Made with care for wineries and age-restricted businesses 🍷
