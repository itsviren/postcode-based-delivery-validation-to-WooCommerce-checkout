<?php
/**
 * Plugin Name: WooCommerce Delivery Postcode Validator
 * Plugin URI: #
 * Description: Adds postcode-based delivery validation to WooCommerce checkout.
 * Version: 1.0.0
 * Author: Virender
 * Text Domain: wc-dpv
 *
 * @package WC_DPV
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin version.
 *
 * Used for asset versioning and cache busting.
 */
define('WC_DPV_VERSION', '1.0.0');

/**
 * Absolute path to the plugin directory.
 *
 * Example:
 * wp-content/plugins/wc-delivery-postcode-validator/
 */
define(
    'WC_DPV_PLUGIN_PATH',
    plugin_dir_path(__FILE__)
);

/**
 * Plugin URL.
 *
 * Used for loading assets such as JavaScript and CSS files.
 */
define(
    'WC_DPV_PLUGIN_URL',
    plugin_dir_url(__FILE__)
);

/**
 * Load the plugin bootstrap loader.
 */
require_once WC_DPV_PLUGIN_PATH . 'includes/class-loader.php';

/**
 * Initialize the plugin.
 */
new WC_DPV_Loader();