jQuery(function ($) {

    const button = $('#place_order');

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

                $('.wc-dpv-message').remove();

                if (!response.data.valid) {

                    button.prop('disabled', true);

                    $('#billing_delivery_postcode').after(
                        '<p class="wc-dpv-message" style="color:red;">'
                        + response.data.message +
                        '</p>'
                    );

                } else {

                    button.prop('disabled', false);

                    $('#billing_delivery_postcode').after(
                        '<p class="wc-dpv-message" style="color:green;">'
                        + response.data.message +
                        '</p>'
                    );
                }
            }
        });
    }

    // Debounced live validation
    let timeout;

    $(document).on(
        'keyup',
        '#billing_delivery_postcode',
        function () {

            clearTimeout(timeout);

            const postcode = $(this).val().trim();

            timeout = setTimeout(function () {

                if (postcode.length === 6) {
                    validatePostcode(postcode);
                }

            }, 100);

        }
    );

});