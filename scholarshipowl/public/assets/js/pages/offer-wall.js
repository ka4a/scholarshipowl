(function($) {

    var fixBannerHeaderHeight = function() {
      var $bannerTitles = $('.offers-tablet-desktop .banner .banner-title'),
        heights = $bannerTitles
        .map(function () {
          return $(this).height()
        })
        .get();

      $bannerTitles
        .css('height', Math.max.apply(null, heights));
    };

    $(fixBannerHeaderHeight);
})(jQuery);
