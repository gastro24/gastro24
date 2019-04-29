(function ($) {

    function showModal(event)
    {
        var $tr = $(event.currentTarget).parent();
        var modal = $tr.data('modal');

        if (modal) {
            modal.open();
            return;
        }

        modal = new BootstrapDialog({
            closable: true,
            closeByBackdrop: true,
            autodestroy: false,
            title: $.fn.orderDetailModal.i18n.headline
                .replace(/%1\$s/, $tr.data('ordernumber'))
                .replace(/%2\$s/, $tr.data('ordertype')),
            message: $('<div>' + $.fn.orderDetailModal.i18n.loading + ' <span class="yk-icon fa-spinner fa-spin"></span></div>')
                .load(basePath + '/' + lang + '/orders/view?id=' + $tr.data('orderid')),
            buttons: [
                {
                    label: $.fn.orderDetailModal.i18n.button,
                    action: function(dialogItself){
                        dialogItself.close();
                    }
                }
            ]
        });

        $tr.data('modal', modal);
        modal.realize();
        modal.getModalDialog().css('width', '95%');
        modal.open();
    }

    $.fn.orderDetailModal = function()
    {
        return this.each(function() {
            $(this).on('click', 'tbody tr td:not(.not-clickable)', showModal);
        });
    };

    /* document ready */
    $(function() {
        $('#orders-list-container').orderDetailModal();
    });

})(jQuery);

