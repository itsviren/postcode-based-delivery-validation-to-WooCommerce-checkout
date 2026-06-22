<?php
/**
 * Plugin uninstall cleanup.
 *
 * Removes plugin options and order metadata
 * created by WooCommerce Delivery Postcode Validator.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Remove plugin settings
|--------------------------------------------------------------------------
*/
delete_option('wc_dpv_blocked_postcodes');

/*
|--------------------------------------------------------------------------
| Remove saved delivery postcodes from orders
|--------------------------------------------------------------------------
*/
global $wpdb;

$wpdb->query(
    $wpdb->prepare(
        "
        DELETE FROM {$wpdb->postmeta}
        WHERE meta_key = %s
        ",
        '_delivery_postcode'
    )
);