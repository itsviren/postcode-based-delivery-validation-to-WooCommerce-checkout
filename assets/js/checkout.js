jQuery(function ($) {

    $(document).on(
        'blur',
        '#billing_delivery_postcode',
        function () {

            const postcode = $(this).val();

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

                        $('#billing_delivery_postcode')
                            .after(
                                '<span class="wc-dpv-message" style="color:red;">Invalid postcode</span>'
                            );

                    } else {

                        $('#billing_delivery_postcode')
                            .after(
                                '<span class="wc-dpv-message" style="color:green;">Valid postcode</span>'
                            );
                    }
                }
            });

        }
    );

});