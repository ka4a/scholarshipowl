
/*
 * Admin Theme JS
 */

$(document).ready(function () {
	var accordionIcons = {
		header: "ui-icon-circle-arrow-e",
		activeHeader: "ui-icon-circle-arrow-s"
	};

	$(".accordion").accordion({icons: accordionIcons });
	$(".select2").select2({ closeOnSelect: false });
	$('.date_picker').datepicker({ "dateFormat": "yy-mm-dd" });
	$("#tabs").tabs();

	$("[name=phone]").filter("[data-phone-type!=non-us]").inputmask("(999) 999 - 9999", { "placeholder": "(   )     -     ", showMaskOnHover: false });

	$(".ajax_form").on('submit',function(e) {
	    e.preventDefault();
	});

	$('.show-sidebar').on('click', function (e) {
		e.preventDefault();
		$('div#main').toggleClass('sidebar-show');
		setTimeout(MessagesMenuWidth, 250);
	});

	$('.main-menu').on('click', 'a', function (e) {
		var parents = $(this).parents('li');
		var li = $(this).closest('li.dropdown');
		var another_items = $('.main-menu li').not(parents);
		another_items.find('a').removeClass('active');
		another_items.find('a').removeClass('active-parent');
		if ($(this).hasClass('dropdown-toggle') || $(this).closest('li').find('ul').length == 0) {
			$(this).addClass('active-parent');
			var current = $(this).next();
			if (current.is(':visible')) {
				li.find("ul.dropdown-menu").slideUp('fast');
				li.find("ul.dropdown-menu a").removeClass('active')
			}
			else {
				another_items.find("ul.dropdown-menu").slideUp('fast');
				current.slideDown('fast');
			}
		}
		else {
			if (li.find('a.dropdown-toggle').hasClass('active-parent')) {
				var pre = $(this).closest('ul.dropdown-menu');
				pre.find("li.dropdown").not($(this).closest('li')).find('ul.dropdown-menu').slideUp('fast');
			}
		}
		if ($(this).hasClass('active') == false) {
			$(this).parents("ul.dropdown-menu").find('a').removeClass('active');
			$(this).addClass('active')
		}
		if ($(this).hasClass('ajax-link')) {
			e.preventDefault();
			if ($(this).hasClass('add-full')) {
				$('#content').addClass('full-content');
			}
			else {
				$('#content').removeClass('full-content');
			}

			var url = $(this).attr('href');
			window.location = url;
		}
		if ($(this).attr('href') == '#') {
			e.preventDefault();
		}
	});

	var height = window.innerHeight - 49;
	$('#main').css('min-height', height)
		.on('click', '.expand-link', function (e) {
			var body = $('body');
			e.preventDefault();
			var box = $(this).closest('div.box');
			var button = $(this).find('i');
			button.toggleClass('fa-expand').toggleClass('fa-compress');
			box.toggleClass('expanded');
			body.toggleClass('body-expanded');
			var timeout = 0;
			if (body.hasClass('body-expanded')) {
				timeout = 100;
			}
			setTimeout(function () {
				box.toggleClass('expanded-padding');
			}, timeout);
			setTimeout(function () {
				box.resize();
				box.find('[id^=map-]').resize();
			}, timeout + 50);
		})
		.on('click', '.close-link', function (e) {
			e.preventDefault();
			var content = $(this).closest('div.box');
			content.remove();
		});
	$('.box-name, .collapse-link').on('click', function (e) {
		e.preventDefault();
		var box = $(this).closest('div.box');
		var button = $(this).find('i.fa-chevron-down');
		var button2 = $(this).find('i.fa-chevron-up');
		var content = box.find('div.box-content');
		content.slideToggle('fast');
		button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
		button2.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
		setTimeout(function () {
			box.resize();
			box.find('[id^=map-]').resize();
		}, 50);
	});
    tinymce.init({
        forced_root_block: false,
        remove_trailing_brs: false,
        selector: ".tinymce",
        menubar : false,
        verify_html: false,
        plugins: "code",
        toolbar: "undo redo | styleselect fontsizeselect | bold italic | link | bullist numlist | code",
        style_formats: [
            {title: "Headers", items: [
                {title: "Header 1", format: "h1"},
                {title: "Header 2", format: "h2"},
                {title: "Header 3", format: "h3"},
                {title: "Header 4", format: "h4"},
                {title: "Header 5", format: "h5"},
                {title: "Header 6", format: "h6"}
            ]},
            {title: "Inline", items: [
                {title: "Bold", icon: "bold", format: "bold"},
                {title: "Italic", icon: "italic", format: "italic"},
                {title: "Underline", icon: "underline", format: "underline"},
                {title: "Code", icon: "code", format: "code"}
            ]}
        ]
    });
});
