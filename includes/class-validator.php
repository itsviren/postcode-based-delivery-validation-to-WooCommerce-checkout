<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Validator
{
    public static function is_valid($postcode)
    {
        return preg_match(
            '/^[0-9]{6}$/',
            $postcode
        );
    }
}