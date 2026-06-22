<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Plugin
{
    public function __construct()
    {
        
        $this->init_classes();
    }

    private function init_classes()
    {
        new WC_DPV_Checkout();
        new WC_DPV_Order();
        new WC_DPV_Session();
        new WC_DPV_Settings();
    }
}