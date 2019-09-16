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

    var matched, browser;

    /* workaround for $.browser error with deprecated jquery version */
    $.uaMatch = function( ua ) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
            /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
            /(msie) ([\w.]+)/.exec( ua ) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
            [];

        return {
            browser: match[ 1 ] || "",
            version: match[ 2 ] || "0"
        };
    };

    matched = $.uaMatch( navigator.userAgent );
    browser = {};

    if ( matched.browser ) {
        browser[ matched.browser ] = true;
        browser.version = matched.version;
    }

    // Chrome is Webkit, but Webkit is also Safari.
    if ( browser.chrome ) {
        browser.webkit = true;
    } else if ( browser.webkit ) {
        browser.safari = true;
    }

    $.browser = browser;

    $(function() {
        $("#jobs-list-filter input[name=q]").autocomplete({
            source : function(request, response) {
                var searchTerm = $("#jobs-list-filter input[name=q]").val();
                var URL = $("#jobs-list-filter input[name=q]").attr('data-url');
                $.ajax({
                    url : URL + '/de/job/suggest?q='+ searchTerm,
                    dataType: 'json',
                    success : function(data) {
                        response( data );
                    },
                    error : function(error) {
                        console.log('Error while fetching solr suggestions');
                    }
                });
            },
            minLength : 1,
            select: function (event, ui) {
                // remove bold html tag
                var StrippedString = ui.item.label.replace(/(<([^>]+)>)/ig,"");
                $('#jobs-list-filter input[name=q]').val(StrippedString);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
        };

        /*
         * remove q query param from url if value is empty
         */
        $('.search-form').submit(function( event ) {
            var searchInput = $(this).find('input.form-control.ui-autocomplete-input[name="q"]');
            if (!searchInput.val()) {
                var uri = $(this).attr('action');
                uri += (uri.match(/\?/) ? '&' : '?') + 'clear=1';
                window.location.href = uri;
                return false;
            }
        });
    });

})(jQuery, window);
 
