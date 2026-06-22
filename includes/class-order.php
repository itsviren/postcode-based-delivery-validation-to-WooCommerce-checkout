<?php

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles WooCommerce order integration.
 *
 * Responsible for:
 * - Saving delivery postcode to order meta
 * - Displaying postcode on the Thank You page
 * - Displaying postcode in WooCommerce Admin Order details
 */
class WC_DPV_Order
{
    /**
     * Register WooCommerce order-related hooks.
     */
    public function __construct()
    {
        add_action(
            'woocommerce_checkout_create_order',
            [$this, 'save_postcode_to_order'],
            10,
            2
        );

        add_action(
            'woocommerce_thankyou',
            [$this, 'display_postcode_on_thankyou']
        );

        add_action(
            'woocommerce_admin_order_data_after_billing_address',
            [$this, 'display_admin_postcode']
        );
    }

    /**
     * Save delivery postcode to WooCommerce order meta.
     *
     * Executes when an order is created during checkout.
     *
     * @param WC_Order $order WooCommerce order object.
     * @param array    $data  Checkout form data.
     *
     * @return void
     */
    public function save_postcode_to_order($order, $data)
    {
        if (!empty($data['billing_delivery_postcode'])) {

            $postcode = sanitize_text_field(
                $data['billing_delivery_postcode']
            );

            $order->update_meta_data(
                '_delivery_postcode',
                $postcode
            );
        }
    }

    /**
     * Display delivery postcode on the WooCommerce Thank You page.
     *
     * Allows customers to verify the postcode used
     * for delivery after placing an order.
     *
     * @param int $order_id WooCommerce order ID.
     *
     * @return void
     */
    public function display_postcode_on_thankyou($order_id)
    {
        $order = wc_get_order($order_id);

        if (!$order) {
            return;
        }

        $postcode = $order->get_meta(
            '_delivery_postcode'
        );

        if (empty($postcode)) {
            return;
        }

        echo '<p>';

        echo '<strong>' .
            esc_html__(
                'Delivery Postcode:',
                'wc-dpv'
            ) .
            '</strong> ';

        echo esc_html($postcode);

        echo '</p>';
    }

    /**
     * Display delivery postcode in WooCommerce Admin Order details.
     *
     * Helps store administrators quickly identify
     * the customer's delivery postcode.
     *
     * @param WC_Order $order WooCommerce order object.
     *
     * @return void
     */
    public function display_admin_postcode($order)
    {
        $postcode = $order->get_meta(
            '_delivery_postcode'
        );

        if (!$postcode) {
            return;
        }

        echo '<p>';

        echo '<strong>' .
            esc_html__(
                'Delivery Postcode:',
                'wc-dpv'
            ) .
            '</strong> ';

        echo esc_html($postcode);

        echo '</p>';
    }
}