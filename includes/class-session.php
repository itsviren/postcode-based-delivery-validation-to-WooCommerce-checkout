<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Session
{
    public function set_postcode($postcode)
    {
        WC()->session->set(
            'delivery_postcode',
            $postcode
        );
    }

    public function get_postcode()
    {
        return WC()->session->get(
            'delivery_postcode'
        );
    }
}