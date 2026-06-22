<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Checkout
{
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

    wp_localize_script(
        'wc-dpv-checkout',
        'wc_dpv',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wc_dpv_nonce'),
        ]
    );
}
public function ajax_validate_postcode()
{
    check_ajax_referer(
        'wc_dpv_nonce',
        'nonce'
    );

    $postcode = sanitize_text_field(
        wp_unslash($_POST['postcode'] ?? '')
    );

    $is_valid = WC_DPV_Validator::is_valid(
        $postcode
    );

    wp_send_json_success([
        'valid' => $is_valid,
    ]);
}
public function add_delivery_postcode_field($fields)
{
    // Remove default WooCommerce postcode field
    unset($fields['billing']['billing_postcode']);

    // Add custom delivery postcode field
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

public function validate_delivery_postcode()
{
    if (
        ! isset($_POST['billing_delivery_postcode'])
    ) {
        return;
    }

    $postcode = sanitize_text_field(
        wp_unslash($_POST['billing_delivery_postcode'])
    );

    if (!WC_DPV_Validator::is_valid($postcode)) {

        wc_add_notice(
            __('Please enter a valid 6-digit delivery postcode.', 'wc-dpv'),
            'error'
        );
    }
}


}
