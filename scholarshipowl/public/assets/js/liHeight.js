jQuery(document).ready(function($) {

    $('a#upgrade').on('click', function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var liHeight = $('.selected li').delay(5000).outerHeight();
            $('.selected li').attr('style', 'height:' + liHeight );
        });
    });

});