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
        // HINT: copied from core.searchform.js
        // url should be set, also when ajax is used
        function serializeForm($form)
        {
            var data = $form.serializeArray();
            var processed = [];
            var parsed = {};
            var multiValues = $form.data('multivalues') || {};

            $.each(data, function(i, item) {
                if (-1 !== $.inArray(item.name, processed)) { return; }

                if (item.name.match(/\[\]$/)) {
                    var $element = $form.find('select[name="' + item.name + '"]');
                    var parsedName = item.name.slice(0,-2);
                    var separator = multiValues.hasOwnProperty(parsedName) ? multiValues[parsedName] : ',';

                    if ($element.length) {
                        var value = $element.val();
                    } else {
                        var value = [];
                        $form.find('[name="' + item.name + '"]:checked').each(function() {
                            value.push($(this).val());
                        });
                    }

                    parsed[separator+parsedName] = value.join(separator);
                    processed.push(item.name);

                } else {
                    parsed[item.name] = item.value;
                }
            });

            return toQuery(parsed, false);

        }

        function toQuery(data, encode)
        {
            var queryParts = [];
            $.each(data, function(name, value) {
                value=(encode) ? encodeURIComponent(value): value;
                name=(encode) ? encodeURIComponent(name) : name;
                queryParts.push(name + '=' + value);
            });

            return queryParts.join('&');
        }

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
        $('.search-form').submit(function( event, data) {
            var searchInput = $(this).find('input.form-control.ui-autocomplete-input[name="q"]');
            var locationInput = $(this).find('select.geoselect[name="l"]');
            var hasFacet = $(this).find('input.facet-param');

            if (data instanceof Object && data.forceAjax) {
                var formQuery      = serializeForm($(this));
                var formUri = $(this).attr('action') + '?' + formQuery;
                if (searchInput.val() == "" && !locationInput.val() && hasFacet.length < 1) {
                    formUri = $(this).attr('action');
                    formUri += (formUri.match(/\?/) ? '&' : '?') + 'clear=1';
                }
                window.history.pushState("", "", formUri);
                return;
            }

            if (searchInput.val() == "" && !locationInput.val() && hasFacet.length < 1) {
                var uri = $(this).attr('action');
                uri += (uri.match(/\?/) ? '&' : '?') + 'clear=1';
                window.location.href = uri;
                return false;
            }
        });
    });

})(jQuery, window);
 
