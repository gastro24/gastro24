;(function ($) {
    var $addressForm;

    function toggleAddressForm()
    {
        if ($(this).prop('checked')) {
            console.log('checkbox checked');
            $addressForm.slideDown();
            // $pdfInput.slideUp();
            // $pdfInput.find('input').val('');
            // $htmlInput.slideUp();
        } else {
            console.log('checkbox not checked');
            $addressForm.slideUp();
            // $pdfInput.slideUp();
            // $pdfInput.find('input').val('');
            // $htmlInput.slideDown();
        }
    }

    $(function() {
        $form = $('#form-registration');
        var $differentAddressCheckbox = $('#register-differentInvoiceAddress');

        $addressForm = $form.find('#registration-registration-registerAddressFieldset');
        $differentAddressCheckbox.click(toggleAddressForm);
    });

})(jQuery);

