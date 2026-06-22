<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Order
{
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