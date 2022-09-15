$(document).ready(function() {
	/*   $("#dialog").dialog({
	 autoOpen: false,
	 modal: true
	 });
	 */
	//positioning of the head
	if($('.centralized').width() < $(window).width() ) {xW = 50;} else {xW = 0;}
	$('.btn_scroll2').css('max-width', (($('.centralized').width())/2)  );
	$('.btn_scroll2').css('max-width', (($('.centralized').width()))  );
	$('.btn_scroll2').css('left', ($('.page-header').width()/2)  - ($('.btn_scroll2').width()/2) );
	$(window).resize(function(){
		if($('.centralized').width() < $(window).width() ) {xW = 50;} else {xW = 0;}
		$('.btn_scroll2').css('max-width', (($('.centralized').width())/2) );
		$('.btn_scroll2').css('max-width', (($('.centralized').width()))  );
		$('.btn_scroll2').css('left', ($('.page-header').width()/2)  - ($('.btn_scroll2').width()/2) );
		//always center .centralized
		$('.centralized').css({marginTop:'-' + ( $(window).height() - $('.centralized').height()  )/2 + 'px !important' });
		//check if scroll down button is over next button
		if(($('.RegisterButton').offset().top  + $('.RegisterButton').height() + 10) > $('.btn_scroll').offset().top ) {

			$('.btn_scroll').addClass('hidden');
		} else {

			$('.btn_scroll').removeClass('hidden');
		}

	});
	//always center .centralized
	$('.centralized').css({marginTop:'-' + ( $(window).height()- $('.centralized').height()   )/2 + 'px !important' });
	//check if scroll down button is over next button
	if($('.RegisterButton').offset().top  + $('.RegisterButton').height() + 10 > $('.btn_scroll').offset().top ) {
		$('.btn_scroll').addClass('hidden');
	} else {
		$('.btn_scroll').removeClass('hidden');
	}
	//small phones < 240
	if($(window).width() < 241  ) {
		resizeSmallPhones();
		$( window ).on( "orientationchange", function( event ) {
			resizeSmallPhones();
		});
	}
	//small phones 320
	if($(window).width() < 321 && (window.innerWidth > window.innerHeight)  ) {
		resizeSmallPhones();
		$( window ).on( "orientationchange", function( event ) {
			resizeSmallPhones();
		});
	}
});//end document ready

function scrollTo(element) {
	$('html,body').animate({
		scrollTop: element.offset().top
	}, 500);
}