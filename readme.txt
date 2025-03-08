=== ZStore - WordPress Store Management Plugin ===
Contributors:      The WordPress Contributors
Tags:              store, ecommerce, mobile-app, api, settings-management
Tested up to:      6.7
Requires at least: 6.7
Requires PHP:      7.4
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive store management plugin that provides a centralized admin panel and API endpoint for mobile app integration.

== Description ==

ZStore is a powerful WordPress plugin designed to help store administrators manage their store settings efficiently through a centralized admin panel. It provides a secure API endpoint for mobile app integration.

**Key Features:**

* Centralized store settings management
* Home page slide management
* Theme customization options
* Working hours configuration
* Checkout form field customization
* Secure API endpoint for mobile app integration
* Performance optimized with caching

**For Developers:**
* RESTful API endpoint
* Secure authentication system
* JSON-based data storage
* Extensible architecture

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/zstore` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to "ZStore" in your WordPress admin menu
4. Configure your store settings:
   * Set your store secret key (required for API access)
   * Configure your store URL and logo
   * Set up your theme colors
   * Configure working hours
   * Customize checkout form fields
   * Manage home page slides

== Configuration ==

1. **Store Secret Key:**
   * Navigate to ZStore > Settings
   * In the General tab, set a secure secret key
   * This key is required for API authentication
   * Use a strong, random key and keep it secure

2. **Theme Settings:**
   * Configure primary and secondary colors
   * Upload your store logo
   * Changes reflect in the mobile app

3. **Working Hours:**
   * Set opening and closing times for each day
   * Toggle store activity status

4. **Checkout Fields:**
   * Enable/disable required checkout fields
   * Customize which information to collect

5. **Slides Management:**
   * Add, edit, and delete home page slides
   * Upload images and set slide order
   * Add titles, descriptions, and links

== API Documentation ==

**Endpoint:** `/wp-json/zstore/v1/settings`
**Method:** GET
**Authentication:** Required (auth_key parameter)

**Request Parameters:**

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 0.1.0 =
* Release

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above. This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation." Arbitrary sections will be shown below the built-in sections outlined above.
