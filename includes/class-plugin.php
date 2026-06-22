<?php

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin bootstrap class.
 *
 * Responsible for initializing all plugin components
 * and registering core functionality.
 *
 * Loaded by:
 * WC_DPV_Loader
 */
class WC_DPV_Plugin
{
    /**
     * Initialize plugin components.
     */
    public function __construct()
    {
        $this->init_classes();
    }

    /**
     * Instantiate all feature classes.
     *
     * WC_DPV_Checkout
     * - Checkout field registration
     * - AJAX validation
     * - Checkout validation
     * - Session handling
     *
     * WC_DPV_Order
     * - Order meta storage
     * - Admin order display
     * - Thank You page display
     *
     * WC_DPV_Settings
     * - Admin settings page
     * - Blocked postcode management
     *
     * @return void
     */
    private function init_classes()
    {
        new WC_DPV_Checkout();
        new WC_DPV_Order();
        new WC_DPV_Settings();
    }
}