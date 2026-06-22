<?php
/**
 * Plugin Name: WooCommerce Delivery Postcode Validator
 * Plugin URI: #
 * Description: Validate delivery postcode during WooCommerce checkout.
 * Version: 1.0.0
 * Author: Virender
 * Text Domain: wc-dpv
 */

if (!defined('ABSPATH')) {
    exit;
}


define('WC_DPV_VERSION', '1.0.0');
define(
    'WC_DPV_PLUGIN_PATH',
    plugin_dir_path(__FILE__)
);

define(
    'WC_DPV_PLUGIN_URL',
    plugin_dir_url(__FILE__)
);

require_once WC_DPV_PLUGIN_PATH . 'includes/class-loader.php';

new WC_DPV_Loader();