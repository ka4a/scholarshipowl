

/* our custom dropdown */
$(function() {

	// add events for open/close, bootstrap 2.3.2 does not have them
	$('.btn-group.custom-select').on('click', function () {
		var $element = $(this);
		$(this).toggleClass('open');
		if ($(this).hasClass('open')) $(this).trigger('open');
		if (!$(this).hasClass('open')) $(this).trigger('close');
		return false;
	});

	$('.btn-group.custom-select ul.dropdown-menu li > a').on('click', function() {
		$('.btn.select' , $(this).closest('.btn-group.custom-select')).html( $(this).html() );
		$(this).closest('.btn-group.custom-select').attr('value', $(this).attr('value')).trigger('change');
	});

	// enable our custom scrollbar
	$('.btn-group.custom-select').on('open', function () {
		$(".scroll", $(this) ).mCustomScrollbar();
	});
	$('.btn-group.custom-select').on('close', function () {
		$(".scroll", $(this) ).mCustomScrollbar("destroy");
	});


	// 
	$('.input-subject a').click(function() {
		$('.input-program ul').addClass('dispNone');
		$('.input-program ul[toggle=' + $(this).attr('value') + ']').removeClass('dispNone');
		$('.input-program .btn.select').html('Program').attr('value','');

	})


})


/* our custom popup input */
$(function() {
	$('.popupInput').click(function() {
		$value = $('a', $(this) ).html();
		var uniqID = 'popup-input-' + Math.round(Math.random() * 100000000000);
		$(this).addClass(uniqID);
		$(this).popover({
			trigger: 'manual',
			html: true,
			content: ' ',
			container: 'body',
			placement: 'bottom',
			template: '<div triggerer="' + uniqID + '" class="popover popup-input"><div class="arrow"></div><!--<h3 class="popover-title"></h3>--><input type="text" value="' + $value + '"><button class="btn">OK</button><!--<div class="popover-content"></div>--></div>'
		})
		$(this).popover('show')
		return false;
	})
	$('.popupSelect').click(function() {

		var uniqID = 'popup-select-' + Math.round(Math.random() * 100000000000);
		$(this).addClass(uniqID);
		$(this).popover({
			trigger: 'manual',
			html: true,
			content: '<div class="dropdown open"><ul class="dropdown-menu">' + $('.dropdown-menu',$(this)).html() + '</ul></div>' ,
			container: 'body',
			placement: 'bottom',
			template: '<div triggerer="' + uniqID + '" class="popover popup-select"><div class="arrow"></div><div class="popover-content"></div></div>'
		})
		$(this).on('show', function(a,b,c) {
			setTimeout(function() {
				$(".scroll", $('.popover') ).mCustomScrollbar("destroy");
				$(".scroll", $('.popover') ).mCustomScrollbar();
			},200)

		})
		$(this).on('hide', function() {})
		$(this).popover('show')
		return false;
	})
})

$(document).on('click','.popup-input .btn', function() {
	var value = $('input[type=text]',$(this).closest('.popup-input')).val()
	$('.' + $(this).closest('.popup-input').attr('triggerer')).popover("destroy")
	$('.' + $(this).closest('.popup-input').attr('triggerer')).trigger('change', [value])

	return false;
})
$(document).on('click','.popup-select ul li > a', function() {
	var value = $(this).html()
	$('.' + $(this).closest('.popup-select').attr('triggerer')).popover("destroy")
	$('.' + $(this).closest('.popup-select').attr('triggerer')).trigger('change', [value])
	return false;
})

$(document).on('click','.popup-input,.popup-select', function() {
	return false;
})

function resizeSmallPhones(){
	$('.page-header .top .text .bold').addClass('transform');
	$('.page-header').addClass('min350');
	$('.logo').addClass('transform');
	$('.box_bg').addClass('transform');
	$('.box_content').addClass('transform');
	$('.centralized').addClass('transform');
	//$('.social').addClass('transform');
	$('.footer').addClass('transform');
	if($(window).width() < 177) {$('.page-header').addClass('min220');}
}
