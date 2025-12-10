# Hot Rocket Age Gate - WordPress Plugin

A modern, customizable age verification plugin built for WordPress and WooCommerce - perfect for wine shops, breweries, dispensaries, and any age-restricted content.

## Features

✅ **Modern UI Design** - Clean, contemporary popup modal with smooth animations
✅ **Fully Customizable** - Control colors, transparency, text, logo, and behavior
✅ **Cookie-Based Tracking** - Remember verified users for configurable duration
✅ **Mobile Responsive** - Works perfectly on all devices
✅ **Easy to Use** - Simple admin interface with live preview capability
✅ **GDPR Friendly** - Consent-based with "Remember me" option
✅ **Custom Logo Upload** - Use your own branding
✅ **Flexible Sizing** - Choose popup size (Small/Medium/Large) and logo size independently
✅ **Multiple Size Options** - 3 sizes for both popup window and logo/icon

## Installation

1. **Upload the Plugin**
   - Upload the `hotrocket-age-gate` folder to `/wp-content/plugins/` directory
   - Or zip the folder and upload via WordPress admin (Plugins → Add New → Upload)

2. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "Hot Rocket Age Gate" and click "Activate"

3. **Configure Settings**
   - Go to Settings → Age Gate
   - Customize your preferences
   - Click "Save Settings"

## Configuration Options

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

### Design & Appearance
- **Custom Logo/Icon** - Upload your own logo image or use the default wine glass icon
- **Logo/Icon Size** - Choose from Small (48px), Medium (64px), or Large (96px)
- **Popup Window Size** - Choose from Small (320px), Medium (400px), or Large (500px)
- **Overlay Transparency** - Control background opacity (0-100%)
- **Overlay Color** - Background color behind popup
- **Popup Background Color** - Modal background color
- **Primary Button Color** - "Yes" button color
- **Text Color** - Main text color in popup
- **Button Text Color** - Text color on "Yes" button

## Default Settings

The plugin comes pre-configured with sensible defaults:

- **Title**: "Welcome to our site"
- **Message**: "Please confirm that you are over 18 years old to enter this site."
- **Age Limit**: 18 years
- **Cookie Duration**: 30 days
- **Popup Size**: Large (500px)
- **Logo Size**: Medium (64px)
- **Primary Color**: #ff6b35 (Hot Rocket Orange)
- **Overlay Opacity**: 95%

## Testing Your Changes

After saving settings:

1. **Method 1**: Clear your browser cookies
   - Use the "Clear Age Verification Cookie" button in admin
   - Or manually clear cookies for your site

2. **Method 2**: Use incognito/private browsing mode
   - Open your site in a private window
   - The age gate will appear on every visit

## File Structure

```
hotrocket-age-gate/
├── hotrocket-age-gate.php         # Main plugin file
├── admin/
│   └── class-age-gate-admin.php   # Admin settings page
├── includes/
│   └── class-age-gate-frontend.php # Frontend display logic
├── assets/
│   ├── css/
│   │   └── age-gate.css           # Popup styles
│   └── js/
│       └── age-gate.js            # User interaction handling
└── README.md                      # This file
```

## How It Works

1. **First Visit**: User sees the age verification popup
2. **User Clicks "Yes"**: Cookie is set, popup disappears
3. **Return Visits**: Cookie is checked, no popup shown
4. **User Clicks "No"**: Redirected to specified URL
5. **Cookie Expiration**: After set duration, verification required again

## Customization Tips

### Matching Your Brand
- Use your brand colors in the Design settings
- Upload your logo for consistent branding
- Adjust overlay transparency for different effects
- Customize button text to match your tone

### High Contrast Design
- Set overlay opacity to 98-100%
- Use dark overlay with light popup background
- Choose contrasting colors for better readability

### Subtle Design
- Lower overlay opacity to 70-80%
- Use lighter overlay colors
- Match popup colors to your site theme

## Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Use Cases

Perfect for:
- 🍷 Wine & Liquor Stores
- 🍺 Breweries & Taprooms
- 🌿 Dispensaries
- 🎰 Gaming & Betting Sites
- 🎬 Adult Content
- 🚬 Tobacco Products
- Any age-restricted content

## Support

For issues or questions:
- Check your browser console for JavaScript errors
- Ensure cookies are enabled in your browser
- Try clearing all site data and testing again

## Version

**1.0.0** - Initial Release

## License

GPL v2 or later

---

Made with ❤️ by Hot Rocket 🚀
