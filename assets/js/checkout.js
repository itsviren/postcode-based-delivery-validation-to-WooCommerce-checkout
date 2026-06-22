jQuery(function ($) {

    const button = $('#place_order');

    /**
     * Display validation message.
     *
     * @param {string} message
     * @param {boolean} isValid
     */
    function showMessage(message, isValid) {

        $('.wc-dpv-message').remove();

        const color = isValid ? 'green' : 'red';

        $('#billing_delivery_postcode').after(
            '<p class="wc-dpv-message" style="color:' +
            color +
            ';">' +
            message +
            '</p>'
        );
    }

    /**
     * Validate postcode via AJAX.
     *
     * @param {string} postcode
     */
    function validatePostcode(postcode) {

        $.ajax({
            url: wc_dpv.ajax_url,
            type: 'POST',
            data: {
                action: 'wc_dpv_validate_postcode',
                nonce: wc_dpv.nonce,
                postcode: postcode
            },

            success: function (response) {

                if (!response.success) {
                    button.prop('disabled', true);
                    return;
                }

                if (!response.data.valid) {

                    button.prop('disabled', true);

                    showMessage(
                        response.data.message,
                        false
                    );

                } else {

                    button.prop('disabled', false);

                    showMessage(
                        response.data.message,
                        true
                    );
                }
            },

            error: function () {

                button.prop('disabled', true);

                showMessage(
                    'Unable to validate postcode. Please try again.',
                    false
                );
            }
        });
    }

    /**
     * Live postcode validation.
     */
    $(document).on(
        'keyup',
        '#billing_delivery_postcode',
        function () {

            const postcode = $(this).val().trim();

            $('.wc-dpv-message').remove();

            /**
             * Empty field.
             */
            if (postcode.length === 0) {

                button.prop('disabled', true);

                return;
            }

            /**
             * Wait until 6 digits entered.
             */
            if (postcode.length < 6) {

                button.prop('disabled', true);

                showMessage(
                    'Please enter a valid 6-digit postcode.',
                    false
                );

                return;
            }

            validatePostcode(postcode);
        }
    );

});