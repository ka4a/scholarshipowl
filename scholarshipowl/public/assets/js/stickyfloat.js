$(document).ready(function() {
	/*==========  My Apllications SUBMIT button floater    ==========*/
	$('#floatdiv').stickyfloat({ duration: 400 });	
	var lastScrollTop = 0;
	$(window).scroll(function(event){
		var st = $(this).scrollTop();
		if (st > lastScrollTop){
		$('.transition400').removeClass('kepp-apply');
		} else {
		$('.transition400').addClass('kepp-apply');
		}
		lastScrollTop = st;
		if ($(window).scrollTop() < 360 ){
			$('.transition400').addClass('move-apply');
		} 
		else {
			$('.transition400').removeClass('move-apply');
		};   	
		if($(window).scrollTop() == 0){
			$('.transition400').removeClass('kepp-apply');
		} 
		else {	
		};  
		if ($(window).scrollTop() < 220 ){
			$('.move-my-apps').addClass('move-apply');
		} 
		else {
			$('.move-my-apps').removeClass('move-apply');
		};   	
		if($(window).scrollTop() == 0){
			$('.move-my-apps').removeClass('kepp-apply');
		} 
		else {	
		};  		
	});	

	/*==========  Select page APLLY NOW button floater    ==========*/
	$(window).scroll(function() {    
		var continueTop = $('#comeBack').position().top + $('#comeBack').outerHeight() + $('.fixedRow').outerHeight();
		var scroll = $(window).scrollTop() + $(window).height();
		if (scroll >= continueTop) {
			$('.fixedRow').removeClass('fixedContinue');
		}
		else {
			$('.fixedRow').addClass('fixedContinue');
		}
	});
});