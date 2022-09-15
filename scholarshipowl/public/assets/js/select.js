if($("#select-popup").length){
    if(!readCookie("do-not-show")){
        $("#select-popup").modal("show").one("click", ".buttonSelectProceed", function () {
            if($("#do-not-show").prop("checked")){
                createCookie("do-not-show", 1, 20*365);
            }
        });
    }
}

var isMobileLandscape = window.matchMedia("only screen and (max-width: 768px) and (max-height: 414px)");

var hideHeader = function() {
  setTimeout(function() {
    $('html, body').stop().animate({
      scrollTop: $('html').offset().top + 70
    }, 400);
    $('.navbar').autoHidingNavbar('hide');
  }, 500);
}

if (isMobileLandscape.matches) {
  $(document).ajaxStop(function() {
    hideHeader();
  });
}

$(window).bind('orientationchange', function(event) {
  hideHeader();
});
