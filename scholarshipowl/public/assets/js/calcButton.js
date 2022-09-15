$(document).ready(function() {
	$("#payment-popup").css({position:"relative", visibility:"visible", display:"block"});
	$("#rafMissionTab").css({position:"relative", visibility:"visible", display:"block"});
	$("#waframe").css({'height':($("#sms img").height()+'px')});
	$("#payment-popup").css({ position: "", visibility: "", display: "" });
	$("#rafMissionTab").css({ position: "", visibility: "", display: "" });
});



$(window).resize(function(){
    $("#waframe").css({'height':($("#sms img").height()+'px')});
});
