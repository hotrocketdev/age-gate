# Changelog

All notable changes to HotRocket Age Gate will be documented in this file.

## [2.0.0] - 2025-12-13

### Added
- **SEO-Friendly Bot Detection** - Automatically detects and skips 50+ common search engine bots and crawlers
- **Custom Bot User Agents** - Add your own bot user agents to skip age verification
- **Shortcode Support** - `[age_gate]` shortcode for restricting specific content sections
- **Restriction Modes** - Choose between "Entire Site" or "Selected Content Only" modes
- **Per-Content Age Limits** - Set different age requirements for individual posts/pages
- **Content Exclusion** - Exclude specific content from age verification in "Entire Site" mode
- **Date of Birth Verification** - New verification method where users enter their actual birthdate
- **Customizable Date Formats** - Support for DD/MM/YYYY, MM/DD/YYYY, and YYYY/MM/DD formats
- **Skip Logged-In Users** - Option to bypass age gate for authenticated WordPress users
- **Legal Note Field** - Add custom legal information or disclaimers to the age gate
- **No-Cache Mode** - Disable caching for better compatibility with caching plugins
- **Developer Hooks System** - Extensive action and filter hooks for customization
  - `hotrocket_age_gate_before_modal` action
  - `hotrocket_age_gate_after_modal` action
  - `hotrocket_age_gate_before_buttons` action
  - `hotrocket_age_gate_after_buttons` action
  - `hotrocket_age_gate_bot_list` filter
  - `hotrocket_age_gate_should_show` filter
  - `hotrocket_age_gate_modal_html` filter
  - `HotRocket_Age_Gate_shortcode_is_verified` filter
  - `HotRocket_Age_Gate_shortcode_restriction_html` filter
  - `HotRocket_Age_Gate_metabox_post_types` filter
- **JavaScript Events** - Custom events for age verification actions
  - `hotrocket_age_gate_verified` event
  - `hotrocket_age_gate_denied` event
- **Multilingual Support** - Full compatibility with WPML, Polylang (2.3+), and WP Multilang
- **Translation Ready** - All strings wrapped in translation functions
- **Meta Box for Posts/Pages** - Configure age verification settings per content
- **Comprehensive Documentation** - Added TUTORIAL.md with step-by-step guides
- **Quick Reference Guide** - Added QUICK_REFERENCE.md for developers
- **Implementation Summary** - Detailed feature documentation

### Enhanced
- **Frontend Logic** - Completely rewritten for better performance and flexibility
- **Admin Interface** - New Advanced Settings section with organized options
- **JavaScript** - Enhanced with date validation and age calculation
- **CSS Styling** - Added styles for date inputs, legal notes, and error messages
- **Mobile Responsiveness** - Improved responsive design for date inputs
- **Accessibility** - Better keyboard navigation and screen reader support
- **Code Organization** - Separated concerns into dedicated classes
- **Security** - Enhanced input sanitization and validation

### Fixed
- Improved cookie handling for better reliability
- Enhanced mobile display on small screens
- Better compatibility with popular themes
- Fixed potential conflicts with other popup plugins

### Changed
- Updated plugin version to 2.0.0
- Reorganized file structure for better maintainability
- Improved code documentation and inline comments
- Enhanced error handling and validation

### Developer Notes
- Added `HotRocket_Age_Gate_Bot_Detector` class for bot detection
- Added `HotRocket_Age_Gate_Shortcode` class for shortcode functionality
- Added `HotRocket_Age_Gate_Metabox` class for per-content settings
- Expanded `HotRocket_Age_Gate_Frontend` with new features
- Enhanced `HotRocket_Age_Gate_Admin` with new settings fields
- All classes follow WordPress coding standards
- Extensive inline documentation added

## [1.0.0] - 2024-11-15

### Added
- Initial release
- Basic age verification popup
- Simple Yes/No verification method
- Cookie-based user tracking
- Customizable design options
  - Custom logo upload
  - Logo size options (Small, Medium, Large)
  - Popup size options (Small, Medium, Large)
  - Color customization (overlay, popup, buttons, text)
  - Transparency control
- Content customization
  - Welcome title
  - Welcome message
  - Button text (Yes/No)
- General settings
  - Enable/disable toggle
  - Age limit configuration
  - Cookie duration (1-365 days)
  - Redirect URL for denied users
  - "Remember me" checkbox option
- Mobile responsive design
- Smooth animations and transitions
- WordPress admin settings page
- Media library integration for logo upload
- Color picker for design options
- Live preview capability
- Cookie clearing utility

### Features
- Modern, clean UI design
- Fully customizable appearance
- Mobile-first responsive design
- Easy-to-use admin interface
- No jQuery conflicts
- Lightweight and fast
- GPL v2 licensed

---

## Version Numbering

This project follows [Semantic Versioning](https://semver.org/):
- **MAJOR** version for incompatible API changes
- **MINOR** version for new functionality in a backwards compatible manner
- **PATCH** version for backwards compatible bug fixes

## Support

For support, feature requests, or bug reports:
- Email: support@hotrocket.dev
- Documentation: See TUTORIAL.md and README.md

---

Made with care for wineries and age-restricted businesses 🍷
