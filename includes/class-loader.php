<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Loader
{
    public function __construct()
    {
        
        $this->load_dependencies();
        $this->init_plugin();
    }

    private function load_dependencies()
    {
       
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-plugin.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-checkout.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-validator.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-order.php';
        require_once WC_DPV_PLUGIN_PATH . 'includes/class-session.php';

        require_once WC_DPV_PLUGIN_PATH . 'admin/class-settings.php';
        
    }

    private function init_plugin()
    {
    
        new WC_DPV_Plugin();
    }
}