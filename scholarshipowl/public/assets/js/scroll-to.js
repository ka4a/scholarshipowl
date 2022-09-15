function scrollNav() {
  $('#scroll_to_cont_form').click(function(){
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 70
    }, 400);
    return false;
  });
  $('.scrollTop a').scrollTop();
}
scrollNav();

function scrollToPayment() {
  $('#scroll_to').click(function(){
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 70
    }, 400);
    $( "input[name='cc-number']" ).focus();
    return false;
  });
  $('.scrollTop a').scrollTop();
}
scrollToPayment();
