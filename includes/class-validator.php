<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Validator
{
    public static function is_valid($postcode)
    {
        $postcode = trim(
            sanitize_text_field($postcode)
        );

        // Check valid Indian pincode format
        if (!preg_match('/^[1-9][0-9]{5}$/', $postcode)) {
            return false;
        }

        $saved_postcodes = get_option(
            'wc_dpv_blocked_postcodes',
            ''
        );

        $blocked_postcodes = array_filter(
            array_map(
                'trim',
                explode("\n", $saved_postcodes)
            )
        );

        if (
            in_array(
                $postcode,
                $blocked_postcodes,
                true
            )
        ) {
            return false;
        }

        return true;
    }
}