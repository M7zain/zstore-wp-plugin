<?php
/**
 * Plugin Name:       Zstore
 * Description:       Mobile app store management plugin
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       zstore
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin constants
define('ZSTORE_VERSION', '0.1.0');
define('ZSTORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ZSTORE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once ZSTORE_PLUGIN_DIR . 'includes/class-zstore-slides.php';
require_once ZSTORE_PLUGIN_DIR . 'includes/class-zstore-admin.php';
require_once ZSTORE_PLUGIN_DIR . 'includes/class-zstore-api.php';
require_once ZSTORE_PLUGIN_DIR . 'includes/class-zstore-settings.php';

/**
 * Plugin activation hook
 */
function zstore_activate() {
	// Create database tables
	require_once ZSTORE_PLUGIN_DIR . 'includes/class-zstore-activator.php';
	Zstore_Activator::activate();
}
register_activation_hook(__FILE__, 'zstore_activate');

/**
 * Plugin deactivation hook
 */
function zstore_deactivate() {
	// Cleanup if needed
}
register_deactivation_hook(__FILE__, 'zstore_deactivate');

/**
 * Initialize the plugin
 */
function zstore_init() {
	// Initialize main plugin classes
	$slides = new Zstore_Slides();
	$admin = new Zstore_Admin();
	$api = new Zstore_API();
}
add_action('plugins_loaded', 'zstore_init');

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_zstore_block_init() {
	register_block_type( __DIR__ . '/build/zstore' );
}
add_action( 'init', 'create_block_zstore_block_init' );
