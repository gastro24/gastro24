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
    jQuery.uaMatch = function( ua ) {
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

    matched = jQuery.uaMatch( navigator.userAgent );
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


    $(function() {
        //var URL_PREFIX = "http://localhost:8983/solr/YawikJobs/suggest?suggest=true&suggest.build=true&wt=json&suggest.q=";
        var URL_PREFIX = "/job/suggest?q=";
        $("#jobs-list-filter input[name=q]").autocomplete({
            source : function(request, response) {
                var URL = URL_PREFIX + $("#jobs-list-filter input[name=q]").val();
                $.ajax({
                    url : URL,
                    success : function(data) {
                        // var dataObject = JSON.parse(data);
                        // var parentNodeInfix = dataObject.suggest.infixSuggester;
                        // var parentNodeFuzzy = dataObject.suggest.fuzzySuggester;
                        // var suggestionsNode = null;
                        // for (var key in parentNodeInfix) {
                        //     suggestionsNode = parentNodeInfix[key].suggestions;
                        //     if(suggestionsNode!=null)
                        //         break;
                        // }
                        // var autocomplete_data = [];
                        // $.each(suggestionsNode, function (i, val) {
                        //     autocomplete_data.push({
                        //         "value": val.term,
                        //         "id": val.term,
                        //         "label": val.term
                        //     });
                        // });

                        response( data );
                    },
                    error : function(error) {
                        console.log('error');
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
    });

})(jQuery, window); 
 
