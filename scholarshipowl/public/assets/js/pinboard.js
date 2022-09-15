$(".dropdown-menu li").click(function(){
  $(this).parents(".dropdown").find('.dropdown-toggle').html(
  $(this).text()+" <span class=\"caret\"></span>"
  );
});

$(function () {
  $('[data-toggle="popover"]').popover()
})