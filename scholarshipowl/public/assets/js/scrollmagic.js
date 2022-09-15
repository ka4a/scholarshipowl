// init controller
var controller = new ScrollMagic.Controller();

function togglePosition (e) {

			if (e.type == "enter") {
				$(".ApplyButton").removeClass('btn-m-arrow').addClass('btn-arrow');
				$(".scroll-magic").removeClass('section--continue--fixed');
			} else {
				$(".ApplyButton").removeClass('btn-arrow').addClass('btn-m-arrow');
				$(".scroll-magic").addClass('section--continue--fixed');
			}

}

// create a scene
$(function () {
	var scene = new ScrollMagic.Scene({triggerElement: "#continue", triggerHook: 'onEnter', offset: 137})
							.setPin(".scroll-magic--wraper")
							.on("enter leave", togglePosition)
							.setClassToggle(".scroll-magic", "section--continue--static")
							.addTo(controller);

})
