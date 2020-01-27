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
;(function ($) {

    $(function() {
        $('.eq_height .rpt_head').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        });

        // add saved jobs badge
        var savedJobsCount = $('header').data('saved-jobs');
        console.log(savedJobsCount);

        if (savedJobsCount > 0) {
            var savedJobsIcon = $('nav .link__saved-jobs');
            var badge = $('<span>').addClass('badge badge-light').html(savedJobsCount);
            savedJobsIcon.removeClass('empty').prepend(badge);
        }
    });

})(jQuery); 
 
