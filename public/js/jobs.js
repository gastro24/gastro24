;(function ($, window) {

    function onApplyLinkClicked(event)
    {
        var $link = $(event.currentTarget);
        var isExternal = $link.hasClass('external-apply-link');
        var message = (isExternal ? '' : '<p>Um Ihre Bewerbung zu senden, nutzen Sie bitte die Möglichkeit im Inserat</p>')
                    + '<p><strong>Bitte beziehen Sie sich bei Ihrer Bewerbung auf Gastrojob24. Vielen Dank.</strong></p>';
        var uri = $link.attr('href');

        BootstrapDialog.show({
            title: $('<h5 class="modal-title">Jetzt bewerben</h5>'),
            type: BootstrapDialog.TYPE_DEFAULT,
            message: $(message),
            closable: true,
            buttons: [
                {
                    label: 'Weiter',
                    action: function() { window.location.href = uri; },
                    cssClass: 'btn-primary'
                }
            ]
        });

        return false;
    }

    function onInternalApplyLinkClicked(event)
    {
        var $link = $(event.currentTarget);
        var $modal = $('#job-apply-modal');

        $modal.find('.modal-body').html('<iframe style="border: 0 solid black;" src="' + $link.attr('href') + '" width="100%" height="100%"></iframe>');
        $modal.modal('show');

        return false;
    }

    function incrementBadgeCount()
    {
        var badge = $('.nav-recruiting .link__saved-jobs span');
        var searchFormBadge = $('.search-form-container .link__saved-jobs span');
        if (!badge.length) {
            var badge = $('<span>').addClass('badge badge-light').html('0');
            $('.nav-recruiting .link__saved-jobs').removeClass('empty').prepend(badge);
        }

        if (!searchFormBadge.length) {
            var searchFormBadge = $('<span>').addClass('badge badge-light').html('0');
            $('.search-form-container .link__saved-jobs').removeClass('empty').prepend(searchFormBadge);
        }

        var oldValue = badge.html();
        var newValue = parseInt(oldValue) + 1;
        badge.html(newValue);

        var oldSearchFormBadgeValue = searchFormBadge.html();
        var newSearchFormBadgeValue = parseInt(oldSearchFormBadgeValue) + 1;
        searchFormBadge.html(newSearchFormBadgeValue);
    }

    function decrementBadgeCount()
    {
        var badge = $('.nav-recruiting .link__saved-jobs span');
        var searchFormBadge = $('.search-form-container .link__saved-jobs span');
        var oldValue = parseInt(badge.html());
        if (oldValue > 0) {
            var newValue = oldValue - 1;
            badge.html(newValue);
            searchFormBadge.html(newValue);
        }

        if (!newValue) {
            badge.remove();
            $('.search-form-container .link__saved-jobs').addClass('empty');
            $('.nav-recruiting .link__saved-jobs').addClass('empty');
        }
    }

    $(function() {
        var $container = $('#jobs-list-container');

        if ($container.length) {
            $('#jobs-list-container').on('click.jobboard', '.internal-apply-link', onInternalApplyLinkClicked)
                .on('click.jobboard', '.external-apply-link, .no-apply-link', onApplyLinkClicked);

        } else {
            $('a.internal-apply-link').click(onInternalApplyLinkClicked);
            $('a.external-apply-link, a.no-apply-link').click(onApplyLinkClicked);
        }

        function markJob(saveButton)
        {
            var saveText = saveButton.data('text-save');
            var savedText = saveButton.data('text-saved');
            var jobId = saveButton.data('job');
            var updateMethod = (saveButton.hasClass('saved')) ? 'remove' : 'save';

            // call ajax, add link to session list
            $.ajax({
                url : '/' + lang + '/job/' + jobId + '/save',
                type: 'post',
                dataType: 'json',
                data: {
                    id: jobId,
                    action: updateMethod,
                },
                success : function(data) {

                    // update translated text
                    if (updateMethod == 'remove') {
                        saveButton.removeClass('saved').find('span').html(saveText);
                        decrementBadgeCount();
                    }
                    else {
                        saveButton.addClass('saved').find('span').html(savedText);
                        incrementBadgeCount();
                    }
                    console.debug(data)
                },
                error : function(error) {
                    console.log('Error while saving job');
                }
            });
        }

        $('.box__action-buttons button, .apply-button-group .favorite-button, .apply-wrapper .favorite-button').click(function() {
            var saveButton = $(this);
            markJob(saveButton);
        });

        $container.on('yk-paginator-container:loaded.gastro24 g24-jobs:init', function(ev) {
            $('.box__action-buttons button').off("click").on('click' , function() {
                var saveButton = $(this);
                markJob(saveButton);
            });
        });
    });

})(jQuery, window); 