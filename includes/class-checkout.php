<?php

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles WooCommerce checkout functionality.
 *
 * Responsible for:
 * - Adding custom delivery postcode field
 * - AJAX postcode validation
 * - Checkout validation
 * - Session storage
 * - Frontend asset loading
 */
class WC_DPV_Checkout
{
    /**
     * Register checkout-related hooks.
     */
    public function __construct()
    {
        add_filter(
            'woocommerce_checkout_fields',
            [$this, 'add_delivery_postcode_field']
        );

        add_action(
            'woocommerce_checkout_process',
            [$this, 'validate_delivery_postcode']
        );

        add_action(
            'wp_ajax_wc_dpv_validate_postcode',
            [$this, 'ajax_validate_postcode']
        );

        add_action(
            'wp_ajax_nopriv_wc_dpv_validate_postcode',
            [$this, 'ajax_validate_postcode']
        );

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );

        add_action(
            'woocommerce_checkout_update_order_review',
            [$this, 'save_postcode_to_session']
        );
    }

    /**
     * Store delivery postcode in WooCommerce session.
     *
     * Keeps the postcode available throughout
     * the checkout process and page refreshes.
     *
     * @param string $posted_data Serialized checkout form data.
     *
     * @return void
     */
    public function save_postcode_to_session($posted_data)
    {
        parse_str($posted_data, $data);

        if (
            isset($data['billing_delivery_postcode'])
        ) {
            WC()->session->set(
                'billing_delivery_postcode',
                sanitize_text_field(
                    $data['billing_delivery_postcode']
                )
            );
        }
    }

    /**
     * Load frontend assets on WooCommerce checkout page.
     *
     * Enqueues JavaScript responsible for
     * live AJAX postcode validation.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script(
            'wc-dpv-checkout',
            WC_DPV_PLUGIN_URL . 'assets/js/checkout.js',
            ['jquery'],
            WC_DPV_VERSION,
            true
        );

        /**
         * Pass AJAX configuration to JavaScript.
         */
        wp_localize_script(
            'wc-dpv-checkout',
            'wc_dpv',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wc_dpv_nonce'),
            ]
        );
    }

    /**
     * Handle AJAX postcode validation requests.
     *
     * Performs:
     * - Nonce verification
     * - Format validation
     * - Delivery availability validation
     *
     * Returns JSON response used by frontend JavaScript.
     *
     * @return void
     */
    public function ajax_validate_postcode()
    {
        check_ajax_referer(
            'wc_dpv_nonce',
            'nonce'
        );

        $postcode = trim(
            sanitize_text_field(
                wp_unslash($_POST['postcode'] ?? '')
            )
        );

        if (empty($postcode)) {

            wp_send_json_success([
                'valid'   => false,
                'message' => __('Postcode is required.', 'wc-dpv'),
            ]);
        }

        /**
         * Validate Indian postcode format.
         */
        if (!preg_match('/^[1-9][0-9]{5}$/', $postcode)) {

            wp_send_json_success([
                'valid'   => false,
                'message' => __('Please enter a valid 6-digit postcode.', 'wc-dpv'),
            ]);
        }

        /**
         * Check delivery availability.
         */
        if (!WC_DPV_Validator::is_valid($postcode)) {

            wp_send_json_success([
                'valid'   => false,
                'message' => __('Delivery is not available for this postcode.', 'wc-dpv'),
            ]);
        }

        wp_send_json_success([
            'valid'   => true,
            'message' => __('Delivery available.', 'wc-dpv'),
        ]);
    }

    /**
     * Add custom Delivery Postcode field to checkout.
     *
     * Removes WooCommerce default postcode field
     * and replaces it with a dedicated delivery postcode.
     *
     * @param array $fields WooCommerce checkout fields.
     *
     * @return array Modified checkout fields.
     */
    public function add_delivery_postcode_field($fields)
    {
        /**
         * Remove default WooCommerce postcode field.
         */
        unset($fields['billing']['billing_postcode']);

        /**
         * Add custom delivery postcode field.
         */
        $fields['billing']['billing_delivery_postcode'] = [
            'type'        => 'text',
            'label'       => __('Delivery Postcode', 'wc-dpv'),
            'placeholder' => __('Enter delivery postcode', 'wc-dpv'),
            'required'    => true,
            'class'       => ['form-row-wide'],
            'priority'    => 70,
        ];

        return $fields;
    }

    /**
     * Validate postcode during checkout submission.
     *
     * Prevents order placement when:
     * - Postcode format is invalid
     * - Postcode is blocked/non-deliverable
     *
     * @return void
     */
    public function validate_delivery_postcode()
    {
        if (
            !isset($_POST['billing_delivery_postcode'])
        ) {
            return;
        }

        $postcode = sanitize_text_field(
            wp_unslash($_POST['billing_delivery_postcode'])
        );

        /**
         * Validate postcode format.
         */
        if (!preg_match('/^[1-9][0-9]{5}$/', $postcode)) {

            wc_add_notice(
                __('Please enter a valid 6-digit postcode.', 'wc-dpv'),
                'error'
            );

            return;
        }

        /**
         * Validate delivery availability.
         */
        if (!WC_DPV_Validator::is_valid($postcode)) {

            wc_add_notice(
                __('Delivery is not available for this postcode.', 'wc-dpv'),
                'error'
            );
        }
    }
}