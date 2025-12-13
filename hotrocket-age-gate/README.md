# HotRocket Age Gate - WordPress Plugin

A modern, feature-rich age verification plugin for WordPress with SEO-friendly bot detection, customizable restrictions, and multilingual support.

## 🎯 Features

### Core Features
✅ **Modern UI Design** - Clean, contemporary popup modal with smooth animations  
✅ **SEO Friendly** - Automatically detects and skips common bots and crawlers  
✅ **Fully Customizable** - Control colors, transparency, text, logo, and behavior  
✅ **Mobile Responsive** - Works perfectly on all devices  
✅ **Cookie-Based Tracking** - Remember verified users for configurable duration  
✅ **GDPR Friendly** - Consent-based with "Remember me" option  

### Advanced Features
✅ **Multiple Verification Methods** - Simple Yes/No or Date of Birth input  
✅ **Flexible Restrictions** - Restrict entire site or selected content only  
✅ **Per-Content Settings** - Different age limits for individual posts/pages  
✅ **Content Exclusions** - Exclude specific content from age verification  
✅ **Shortcode Support** - `[age_gate]content[/age_gate]` for in-content restrictions  
✅ **Custom Bot Detection** - Add your own bot user agents  
✅ **Skip Logged-In Users** - Option to bypass age gate for authenticated users  
✅ **Customizable Date Format** - DD/MM/YYYY, MM/DD/YYYY, or YYYY/MM/DD  
✅ **Legal Note Field** - Add custom legal information to the age gate  
✅ **Caching Compatible** - Works with caching plugins (with optional no-cache mode)  
✅ **Developer Hooks** - Extensive action and filter hooks for customization  
✅ **Multilingual Ready** - Compatible with WPML, Polylang (2.3+), WP Multilang  

## 📦 Installation

### Method 1: WordPress Admin Upload

1. Download the `hotrocket-age-gate.zip` file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the zip file
4. Click "Install Now" then "Activate"

### Method 2: FTP Upload

1. Extract the zip file
2. Upload the `hotrocket-age-gate` folder to `/wp-content/plugins/`
3. Activate the plugin through WordPress admin

## ⚙️ Configuration

### Basic Setup

1. Go to **Settings → Age Gate**
2. Enable the age gate
3. Set your age limit (default: 18)
4. Configure cookie duration
5. Set redirect URL for users who click "No"
6. Save settings

### General Settings

- **Enable Age Gate** - Turn the popup on/off
- **Age Limit** - Set minimum age (18, 21, etc.)
- **Cookie Duration** - How long to remember verified users (1-365 days)
- **Redirect URL** - Where to send users who click "No"
- **Show "Remember Me"** - Display remember me checkbox

### Content Settings

- **Welcome Title** - Main heading text
- **Welcome Message** - Description text below the title
- **Yes Button Text** - Confirmation button label
- **No Button Text** - Exit button label
- **Legal Note** - Optional legal information (HTML allowed)

### Design & Appearance

- **Custom Logo/Icon** - Upload your own logo or use default wine glass icon
- **Logo/Icon Size** - Small (48px), Medium (64px), or Large (96px)
- **Popup Size** - Small (350px), Medium (420px), or Large (500px)
- **Overlay Transparency** - Control background opacity (0-100%)
- **Overlay Color** - Background color behind popup
- **Popup Background Color** - Modal background color
- **Primary Button Color** - "Yes" button color
- **Text Color** - Main text color in popup
- **Button Text Color** - Text color on "Yes" button

### Advanced Settings

- **Restriction Mode**
  - *Entire Site* - Show on all pages (can exclude specific content)
  - *Selected Content* - Only show on marked content
  
- **Verification Method**
  - *Simple Yes/No* - Quick button-based verification
  - *Date of Birth Input* - Users enter actual birthdate
  
- **Date Format** - DD/MM/YYYY, MM/DD/YYYY, or YYYY/MM/DD
- **Skip Logged-In Users** - Bypass age gate for authenticated users
- **Custom Bot User Agents** - Add custom bots to skip verification
- **Disable Caching** - Enable if having issues with caching plugins

## 🎨 Per-Content Settings

When editing posts or pages, find the **Age Verification Settings** meta box:

- **Require/Exclude Verification** - Mark content for age restriction
- **Custom Age Limit** - Override global age for specific content

**Example:** Set global age to 18, but require 21 for premium wine products.

## 📝 Shortcode Usage

Restrict specific sections of content:

### Basic Usage
```
[age_gate]
This content is only visible to verified users.
[/age_gate]
```

### With Custom Age
```
[age_gate age="21"]
This premium content requires users to be 21+
[/age_gate]
```

### With Custom Message
```
[age_gate age="21" message="You must be 21 to view this premium wine collection"]
Exclusive wine details here...
[/age_gate]
```

## 🔧 Testing Your Changes

### Method 1: Clear Cookies
- Use the "Clear Age Verification Cookie" button in admin
- Reload your website

### Method 2: Incognito Mode
- Open your site in a private/incognito window
- The age gate will appear on every visit

## 🤖 SEO & Bot Detection

The plugin automatically detects and skips 50+ common bots including:
- Googlebot, Bingbot, Yahoo Slurp
- Facebook, Twitter, LinkedIn crawlers
- SEO tools (Ahrefs, SEMrush, Moz, etc.)
- Archive.org, Wayback Machine
- And many more...

### Add Custom Bots
Go to **Settings → Age Gate → Advanced Settings** and add custom user agents (comma or line-separated).

## 🌍 Multilingual Support

Compatible with popular translation plugins:
- **WPML** - Full compatibility
- **Polylang** - Version 2.3+
- **WP Multilang** - Full compatibility

All strings are translatable using standard WordPress translation methods.

## 🔌 Developer Hooks

### Action Hooks

```php
// Before modal renders
add_action('hotrocket_age_gate_before_modal', function($options) {
    // Your code
});

// After modal renders
add_action('hotrocket_age_gate_after_modal', function($options) {
    // Your code
});

// Before buttons
add_action('hotrocket_age_gate_before_buttons', function($options) {
    // Your code
});

// After buttons
add_action('hotrocket_age_gate_after_buttons', function($options) {
    // Your code
});
```

### Filter Hooks

```php
// Modify bot list
add_filter('hotrocket_age_gate_bot_list', function($bots) {
    $bots[] = 'my-custom-bot';
    return $bots;
});

// Control when age gate shows
add_filter('hotrocket_age_gate_should_show', function($should_show, $options) {
    if (is_page('about')) {
        return false;
    }
    return $should_show;
}, 10, 2);

// Modify modal HTML
add_filter('hotrocket_age_gate_modal_html', function($html, $options) {
    return $html;
}, 10, 2);
```

### JavaScript Events

```javascript
// When user verifies age
jQuery(document).on('hotrocket_age_gate_verified', function() {
    console.log('User verified!');
});

// When user denies age
jQuery(document).on('hotrocket_age_gate_denied', function() {
    console.log('User denied');
});
```

## 📁 File Structure

```
hotrocket-age-gate/
├── hotrocket-age-gate.php              # Main plugin file
├── README.md                       # This file
├── TUTORIAL.md                     # Complete tutorial
├── admin/
│   └── class-age-gate-admin.php   # Admin settings page
├── includes/
│   ├── class-age-gate-frontend.php    # Frontend display logic
│   ├── class-age-gate-bot-detector.php # Bot detection
│   ├── class-age-gate-shortcode.php   # Shortcode functionality
│   └── class-age-gate-metabox.php     # Per-content settings
├── assets/
│   ├── css/
│   │   └── age-gate.css           # Popup styles
│   └── js/
│       └── age-gate.js            # User interaction handling
├── languages/
│   └── hotrocket-age-gate.pot          # Translation template
└── landing-page.html              # Marketing page
```

## 🎯 Use Cases

Perfect for:
- 🍷 Wineries and wine shops
- 🍺 Breweries and beer retailers
- 🚬 Tobacco and vape shops
- 🔞 Adult content websites
- 💊 Pharmaceutical sites
- 🎰 Gaming and gambling sites
- Any business requiring age verification

## ❓ FAQ

**Q: Will this affect my SEO?**  
A: No! The plugin automatically detects search engine bots and allows them to crawl your site.

**Q: Can I use different age limits for different pages?**  
A: Yes! Use the custom age field in the post/page editor.

**Q: Does it work with caching plugins?**  
A: Yes, but you may need to enable "Disable Caching" in settings.

**Q: Is it GDPR compliant?**  
A: The plugin only uses a simple cookie. No personal data is collected.

**Q: Can I customize the design?**  
A: Absolutely! Use the built-in design settings or add custom CSS.

## 🔄 Changelog

### Version 2.0.0 (December 2025)
- ✨ Added SEO-friendly bot detection
- ✨ Added custom bot user agents support
- ✨ Added shortcode for in-content restrictions
- ✨ Added restriction modes (entire site vs selected content)
- ✨ Added per-content age limits
- ✨ Added content exclusion option
- ✨ Added date of birth verification method
- ✨ Added customizable date formats
- ✨ Added skip logged-in users option
- ✨ Added legal note field
- ✨ Added no-cache mode
- ✨ Added extensive developer hooks
- ✨ Added multilingual support (WPML, Polylang, WP Multilang)
- 🐛 Improved mobile responsiveness
- 🐛 Enhanced accessibility
- 📚 Added comprehensive documentation

### Version 1.0.0
- 🎉 Initial release

## 📄 License

GPL v2 or later

## 💬 Support

For support, feature requests, or bug reports:
- Email: support@hotrocket.dev
- Documentation: See TUTORIAL.md

---

Made with care for wineries and age-restricted businesses 🍷
