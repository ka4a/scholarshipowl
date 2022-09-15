require('./bootstrap');

$(function() {

    // Mmenu
    $("#nav-menu").mmenu({
        "navbars": [
            {
                "position": "top",
                "content": [ "breadcrumbs", "close" ]
            }
        ],
        extensions: [
            "shadow-page",
            "position-right",
            "fullscreen"
        ],
    }, {
        offCanvas: {
            pageSelector: "#page-wrapper"
        }
    });

    var API = $("#nav-menu").data("mmenu");

    $(".toggle-nav-menu").click(function() {
        API.open();
    });


    // Banners
    $(".page-index .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/index/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-pricing .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/pricing/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-about-us .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/about-us/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-contact-us .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/contact-us/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-faq .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/faq/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-features .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/index/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-courses .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/courses/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-admissions-coaching .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/admissions-coaching/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-interview-preparation .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/interview-preparation/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-personalized-scholarships-list .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/personalized-scholarships-list/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-essay-assistance .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/essay-assistance/banner.jpg',
                valign: 'bottom'
            }
        ],
    });

    $(".page-guidance-for-parents .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/features/guidance-for-parents/banner.jpg',
                valign: 'center'
            }
        ],
    });

    $(".page-privacy-policy .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/privacy-policy/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-terms-of-use .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/terms-of-use/banner.jpg',
                valign: 'top'
            }
        ],
    });

    $(".page-sitemap .Banner").vegas({
        preload: true,
        animation: 'random',
        delay: 7000,
        slides: [
            {
                src: '/imgs/sitemap/banner.jpg',
                valign: 'top'
            }
        ],
    });


    // Nav effect
    var scroll = $(window).scrollTop();
    if (scroll != 0 && scroll > 100) {
        $('.main-nav').removeClass('navbar-static');
        $('.main-nav').addClass('navbar-scroll');
    }

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll != 0 && scroll > 100) {
            $('.main-nav').removeClass('navbar-static');
            $('.main-nav').addClass('navbar-scroll');
        }

        if (scroll <= 100) {
            $('.main-nav').removeClass('navbar-scroll');
            $('.main-nav').addClass('navbar-static');
        } else {
            $('.main-nav').removeClass('navbar-static');
            $('.main-nav').addClass('navbar-scroll');
        }
    });

    var form = $('form#eligibility-check');

    form.submit(function(e) {

        var data = form.serialize();
        var email = $('#eligibility-email').val();
        var errorMessage = '<p class="text-center Util--text-light-primary"><strong>Oops!</strong> Try again in a few moments.</p>';

        if ($.trim(email) != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').attr('value'),
                }
            });

            $.ajax({
                type: 'POST',
                url: 'contact-us/coaching',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                },
            }).done(function(data, textStatus, jqXHR) {
                $('form#eligibility-check').hide();
                $('#form-panel').html('<p class="Util--text-light-primary" id="form-success">Thank you for your submission. Please check your email for the eligibility questionnaire.</p>');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON) {
                    $('#form-panel').html(errorMessage);
                }
            });
        } else {
            $('#form-panel').html(errorMessage);
        }

        e.preventDefault();
    });

});
