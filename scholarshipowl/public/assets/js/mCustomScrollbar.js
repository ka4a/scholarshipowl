$(document).ready(function() {

    $('select.selectpicker').selectpicker(); // bootstrap-select initialization

    $('.scrollbar').mCustomScrollbar({
        theme: "dark-thick",
        contentTouchScroll: false,
        scrollButtons: { enable: false }
    });

    // hide first item from the list
    $('.bootstrap-select ul.dropdown-menu li:first-child').remove();

    // mCustomScrollbar Bootstrap fix
   $(".dropdown-menu, html").on("mouseup pointerup", function(e) {
        $(".dropdown-menu .mCSB_scrollTools").removeClass("mCSB_scrollTools_onDrag");
    }).on("click", function(e) {
        if ($(e.target).parents(".mCSB_scrollTools").length || $(".dropdown-menu .mCSB_scrollTools").hasClass("mCSB_scrollTools_onDrag")) {
            e.stopPropagation();
        }
    });

    // mCustomScrollbar mission table scroll horizontally
    $(".missionAccountTable").mCustomScrollbar({
        axis:"x" // horizontal scrollbar
    });
});