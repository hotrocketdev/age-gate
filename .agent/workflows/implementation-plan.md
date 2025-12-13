---
description: Implementation plan for Vin's Age Gate plugin improvements
---

# Vin's Age Gate - Feature Enhancement Implementation Plan

## Overview
This plan outlines the implementation of advanced features for the WordPress age verification plugin.

## Features to Implement

### 1. ✅ Ask users to verify their age on page load
**Status:** Already implemented
- Current implementation shows popup on page load if cookie not set

### 2. SEO Friendly – Bot Detection
**Implementation:**
- Create bot detection system in frontend class
- Add default list of common bots (Googlebot, Bingbot, etc.)
- Skip age gate for detected bots
- **Files to modify:**
  - `includes/class-age-gate-frontend.php`

### 3. Custom User Agents for Bots
**Implementation:**
- Add admin setting for custom user agent list
- Textarea field for comma-separated user agents
- Merge with default bot list
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`
  - `vins-age-gate.php` (default options)

### 4. Shortcode for In-Content Restrictions
**Implementation:**
- Create shortcode `[age_gate]content[/age_gate]`
- Hide content if user not verified
- Show verification message with link/button
- **Files to modify:**
  - `includes/class-age-gate-frontend.php`
  - Create new file: `includes/class-age-gate-shortcode.php`

### 5. Restrict Entire Site or Selected Content
**Implementation:**
- Add restriction mode setting (entire site / selected content)
- If selected content: only show gate on specific pages/posts
- Add meta box to posts/pages for age restriction
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`
  - Create new file: `includes/class-age-gate-metabox.php`

### 6. Different Age on Individual Content
**Implementation:**
- Add custom age field in post/page meta box
- Override global age limit per content
- **Files to modify:**
  - `includes/class-age-gate-metabox.php`
  - `includes/class-age-gate-frontend.php`

### 7. Exclude Content from "All Content" Mode
**Implementation:**
- Add checkbox in meta box to exclude from age gate
- Check this setting before showing gate
- **Files to modify:**
  - `includes/class-age-gate-metabox.php`
  - `includes/class-age-gate-frontend.php`

### 8. Customizable Date Input Order
**Implementation:**
- Add setting for date format (DD/MM/YYYY, MM/DD/YYYY, YYYY/MM/DD)
- Replace simple yes/no with date input option
- Add toggle between simple mode and date input mode
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`
  - `assets/css/age-gate.css`
  - `assets/js/age-gate.js`

### 9. Omit Logged-In Users
**Implementation:**
- Add checkbox setting to skip age gate for logged-in users
- Check `is_user_logged_in()` before showing gate
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`

### 10. Legal Note/Information
**Implementation:**
- Add textarea for legal note in admin
- Display at bottom of age gate modal
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`
  - `vins-age-gate.php` (default options)

### 11. Non-Caching Version
**Implementation:**
- Add setting to disable caching for age gate
- Add no-cache headers when enabled
- Add JavaScript cache-busting
- **Files to modify:**
  - `admin/class-age-gate-admin.php`
  - `includes/class-age-gate-frontend.php`

### 12. Hooks for Customization
**Implementation:**
- Add action hooks:
  - `vins_age_gate_before_modal`
  - `vins_age_gate_after_modal`
  - `vins_age_gate_before_buttons`
  - `vins_age_gate_after_buttons`
  - `vins_age_gate_verified`
  - `vins_age_gate_denied`
- Add filter hooks:
  - `vins_age_gate_options`
  - `vins_age_gate_modal_html`
  - `vins_age_gate_bot_list`
  - `vins_age_gate_should_show`
- **Files to modify:**
  - `includes/class-age-gate-frontend.php`
  - `assets/js/age-gate.js`

### 13. Multilingual Plugin Compatibility
**Implementation:**
- Add WPML compatibility
- Add Polylang compatibility
- Add WP Multilang compatibility
- Use translation functions for all strings
- Create .pot file for translations
- **Files to modify:**
  - All PHP files (add text domain to strings)
  - Create: `languages/vins-age-gate.pot`

### 14. Improved Documentation
**Implementation:**
- Create comprehensive .doc tutorial
- Include screenshots
- Step-by-step setup guide
- Advanced customization examples
- Troubleshooting section
- **Files to create:**
  - `documentation/tutorial.doc` (or .docx)
  - `documentation/screenshots/` (folder)

## Implementation Order

1. Bot detection & custom user agents (SEO)
2. Logged-in user exemption
3. Restriction modes (entire site vs selected content)
4. Meta box for per-content settings
5. Date input with customizable format
6. Shortcode functionality
7. Legal note field
8. Non-caching option
9. Hooks system
10. Multilingual support
11. Documentation

## File Structure After Implementation

```
vins-age-gate/
├── vins-age-gate.php
├── admin/
│   └── class-age-gate-admin.php
├── includes/
│   ├── class-age-gate-frontend.php
│   ├── class-age-gate-shortcode.php (NEW)
│   ├── class-age-gate-metabox.php (NEW)
│   └── class-age-gate-bot-detector.php (NEW)
├── assets/
│   ├── css/
│   │   └── age-gate.css
│   └── js/
│       └── age-gate.js
├── languages/
│   └── vins-age-gate.pot (NEW)
├── documentation/ (NEW)
│   ├── tutorial.docx
│   └── screenshots/
├── landing-page.html
└── README.md
```

## Testing Checklist

- [ ] Bot detection works correctly
- [ ] Custom user agents are respected
- [ ] Shortcode displays and functions properly
- [ ] Meta box saves and loads correctly
- [ ] Different ages per content work
- [ ] Content exclusion works in "all content" mode
- [ ] Date input validates correctly
- [ ] All date formats work properly
- [ ] Logged-in users are skipped when enabled
- [ ] Legal note displays correctly
- [ ] Non-caching mode works
- [ ] All hooks fire at correct times
- [ ] WPML integration works
- [ ] Polylang integration works
- [ ] WP Multilang integration works
- [ ] All strings are translatable
- [ ] Documentation is complete and accurate
