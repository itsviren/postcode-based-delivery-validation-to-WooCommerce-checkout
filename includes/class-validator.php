<?php

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles delivery postcode validation.
 *
 * Responsible for:
 * - Validating postcode format
 * - Checking blocked/non-deliverable postcodes
 */
class WC_DPV_Validator
{
    /**
     * Validate a delivery postcode.
     *
     * Validation rules:
     * 1. Must be a valid Indian 6-digit postcode.
     * 2. Must not exist in the blocked postcode list.
     *
     * @param string $postcode Customer entered postcode.
     * @return bool True if delivery is allowed, otherwise false.
     */
    public static function is_valid($postcode)
    {
        // Sanitize and normalize input.
        $postcode = trim(
            sanitize_text_field($postcode)
        );

        /**
         * Validate Indian postcode format.
         *
         * Examples:
         * Valid   : 160062
         * Invalid : 12345, ABC123
         */
        if (!preg_match('/^[1-9][0-9]{5}$/', $postcode)) {
            return false;
        }

        /**
         * Retrieve blocked postcodes from plugin settings.
         *
         * Stored as newline-separated values.
         */
        $saved_postcodes = get_option(
            'wc_dpv_blocked_postcodes',
            ''
        );

        /**
         * Convert saved values into an array and
         * remove empty lines.
         */
        $blocked_postcodes = array_filter(
            array_map(
                'trim',
                explode("\n", $saved_postcodes)
            )
        );

        /**
         * Reject checkout if the postcode
         * exists in the blocked postcode list.
         */
        if (
            in_array(
                $postcode,
                $blocked_postcodes,
                true
            )
        ) {
            return false;
        }

        // Postcode is valid and serviceable.
        return true;
    }
}