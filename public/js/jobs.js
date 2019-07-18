/**
 * YAWIK
 *
 * License: MIT
 * (c) 2013 - 2017 CROSS Solution <http://cross-solution.de>
 */

/**
 *
 * Author: Mathias Gelhausen <gelhausen@cross-solution.de>
 */
;(function ($, window) {

    function onPagiantorLoaded()
    {
        $('.featured-image-box').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        });
    }

    function onApplyLinkClicked(event)
    {
        var $link = $(event.currentTarget);
        var isExternal = $link.hasClass('external-apply-link');
        var message = (isExternal ? '' : '<p>Um Ihre Bewerbung zu senden, nutzen Sie bitte die Möglichkeit im Inserat</p>')
                    + '<p><strong>Bitte beziehen Sie sich bei Ihrer Bewerbung auf Gastrojob24.ch. Vielen Dank.</strong></p>';
        var uri = $link.attr('href');

        BootstrapDialog.show({
            title: $('<h5 class="modal-title">Jetzt auf die Stelle bewerben</h5>'),
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
        var badge = $('nav .link__saved-jobs span');
        if (!badge.length) {
            var badge = $('<span>').addClass('badge badge-light').html('0');
            $('nav .link__saved-jobs').removeClass('empty').prepend(badge);
        }

        var oldValue = badge.html();
        console.log(oldValue);
        var newValue = parseInt(oldValue) + 1;
        badge.html(newValue);
    }

    function decrementBadgeCount()
    {
        var badge = $('nav .link__saved-jobs span');
        var oldValue = parseInt(badge.html());
        if (oldValue > 0) {
            var newValue = oldValue - 1;
            badge.html(newValue);
        }

        if (!newValue) {
            badge.remove();
            $('nav .link__saved-jobs').addClass('empty');
        }
    }

    $(function() {
        var $container = $('#jobs-list-container');

        if ($container.length) {
            $('#jobs-list-container').on('yk-paginator-container:loaded.jobboard', onPagiantorLoaded)
                .on('click.jobboard', '.internal-apply-link', onInternalApplyLinkClicked)
                .on('click.jobboard', '.external-apply-link, .no-apply-link', onApplyLinkClicked);


            onPagiantorLoaded();

        } else {
            $('a.internal-apply-link').click(onInternalApplyLinkClicked);
            $('a.external-apply-link, a.no-apply-link').click(onApplyLinkClicked);
        }

        $('.box__job-favorite button').click(function() {
            var saveButton = $(this);
            var saveText = saveButton.data('text-save');
            var savedText = saveButton.data('text-saved');
            var jobLink = $(this).parent().find('h2 > a').attr('href');
            var jobId = jobLink.split('-').pop().replace('.html', '');
            console.debug('Save button clicked');
            console.debug('Job link: ' + jobLink);
            console.debug('Job Id: ' + jobId);

            var updateMethod = (saveButton.hasClass('saved')) ? 'remove' : 'save';

            //var URL = $("#jobs-list-filter input[name=q]").attr('data-url');

            // call ajax, add link to session list
            // TODO: check for saved class, use post method, send save or remove
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
        })
    });

})(jQuery, window); 
 
