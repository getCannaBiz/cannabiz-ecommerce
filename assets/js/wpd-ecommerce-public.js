jQuery(document).ready(function ($) {
    // Active class default.
    paymentTypeActive($);
    // Change radio payment-type.
    $("input[name=payment-type]").change(function() {
        // Remove active class from all other payment types.
        $("input[name=payment-type").parent().parent().removeClass("active");
        // Toggle active classes when clicked.
        $(this).parent().parent().toggleClass("active");

        // Get value.
        var metavalue = this.value;
        // Get name.
        var metaname  = $(this).attr('id');

        $.post( object_name.ajaxurl,{
            action : 'wpd_ecommerce_checkout_settings',
            metavalue : metavalue,
            metaname : metaname,
        },
        // Added a callback so that the call to update the UI waits until the first AJAX call is complete.
        function(data, textStatus, jqXHR) {
            // Refresh table.
            $("table.wpd-ecommerce.widget.checkout tbody tr.total td.total-price").load( object_name.pageURL + " span.total-price" );
        }
        );
    });
});

// Set active class for payment type set in session data.
function paymentTypeActive($) {
    var PAYMENT_TYPE_AMOUNT = object_name.PAYMENT_TYPE_AMOUNT;

    // Check radio button of active payment type.
    $("input[name='payment-type']").each(function(){
        // Check payment-type.
        if ( PAYMENT_TYPE_AMOUNT == $(this).val() ) {
            $(this).parent().parent().toggleClass("active");
            $(this).prop("checked", true);
        }
    });
}
