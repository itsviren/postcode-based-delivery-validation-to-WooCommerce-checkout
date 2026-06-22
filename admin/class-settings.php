<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_DPV_Settings
{
    /**
     * Option name used in wp_options table.
     */
    const OPTION_NAME = 'wc_dpv_blocked_postcodes';

    public function __construct()
    {
        add_action(
            'admin_menu',
            [$this, 'register_menu']
        );

        add_action(
            'admin_init',
            [$this, 'register_settings']
        );
    }

    /**
     * Register submenu under WooCommerce.
     */
    public function register_menu()
    {
        add_submenu_page(
            'woocommerce',
            __('Delivery Postcode Validator', 'wc-dpv'),
            __('Postcode Validator', 'wc-dpv'),
            'manage_woocommerce',
            'wc-dpv-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register setting.
     */
    public function register_settings()
    {
        register_setting(
            'wc_dpv_settings_group',
            self::OPTION_NAME,
            [$this, 'sanitize_postcodes']
        );
    }

    /**
     * Sanitize postcodes before saving.
     */
    public function sanitize_postcodes($input)
    {
        $lines = explode("\n", $input);

        $postcodes = array_map(
            'sanitize_text_field',
            $lines
        );

        $postcodes = array_filter(
            array_map('trim', $postcodes)
        );

        return implode("\n", $postcodes);
    }

    /**
     * Settings page UI.
     */
    public function render_settings_page()
    {
        $saved_postcodes = get_option(
            self::OPTION_NAME,
            ''
        );
        ?>

        <div class="wrap">
            <h1>
                <?php esc_html_e(
                    'Delivery Postcode Validator',
                    'wc-dpv'
                ); ?>
            </h1>

            <form method="post" action="options.php">

                <?php
                settings_fields(
                    'wc_dpv_settings_group'
                );
                ?>

                <table class="form-table">

                    <tr>
                        <th scope="row">
                            <label for="wc_dpv_allowed_postcodes">
                                <?php esc_html_e(
                                    'Blocked Delivery Postcodes',
                                    'wc-dpv'
                                ); ?>
                            </label>
                        </th>

                        <td>
                            <textarea
                                name="<?php echo esc_attr(self::OPTION_NAME); ?>"
                                id="wc_dpv_allowed_postcodes"
                                rows="10"
                                cols="50"
                                class="large-text"
                            ><?php echo esc_textarea($saved_postcodes); ?></textarea>

                            <p class="description">
                                <?php esc_html_e(
                                   'Enter one blocked postcode per line.',
                                    'wc-dpv'
                                ); ?>
                            </p>
                        </td>
                    </tr>

                </table>

                <?php submit_button(); ?>

            </form>
        </div>

        <?php
    }
}