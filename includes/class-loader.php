<?php

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin loader.
 *
 * Responsible for:
 * - Loading all required plugin files
 * - Initializing the plugin bootstrap class
 *
 * This is the first class loaded by the main plugin file.
 */
class WC_DPV_Loader
{
    /**
     * Load dependencies and initialize plugin.
     */
    public function __construct()
    {
        $this->load_dependencies();
        $this->init_plugin();
    }

    /**
     * Load all plugin class files.
     *
     * Includes:
     * - Core plugin bootstrap
     * - Checkout functionality
     * - Postcode validation
     * - Order integration
     * - Admin settings
     *
     * @return void
     */
    private function load_dependencies()
    {
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-plugin.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-checkout.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-validator.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-order.php';

        require_once WC_DPV_PLUGIN_PATH . 'admin/class-settings.php';
    }

    /**
     * Initialize the main plugin class.
     *
     * @return void
     */
    private function init_plugin()
    {
        new WC_DPV_Plugin();
    }
}