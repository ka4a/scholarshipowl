if ($('.selectpicker').length) {
	$('.selectpicker').selectpicker({
		dropupAuto: false
	});

	if ($('.eligibility').length) {
		// hide first item from the list
		$('.bootstrap-select ul.dropdown-menu li:first-child').remove();
	}
}
/*
 * AjaxButton JS Class
 * By Marko Prelic
 */
var AjaxButton = Element.extend({
	onBeforeAction: function() { return true; },
	onBeforeSend: function(request) {},
	onSuccess: function(response) {},
	onError: function(xhr, ajaxOptions, thrownError) {},
	onComplete: function() {},
	requestIsSending: false,

	_init: function(element) {
		this._super(element);
		var caller = this;

		element.click(function(e) {
			e.preventDefault();

			var $performAction = caller.onBeforeAction.apply(caller, arguments);
			if($performAction == false) {
				return false;
			}

			var $token = $("input[name=_token]").val();
			if(typeof $token === "undefined") {
				throw "Token Not Defined";
			}

			var $form = $(this).closest("form");
			var $data = $form.serialize();
			var $action = $form.attr("action");
			var $type = "post";
			var $dataType = "json";

			var $ajax = new Ajax($action, $type, $dataType, $data);

			$ajax.onBeforeSend = function(request) {
				caller.requestIsSending = false;
				request.setRequestHeader("X-CSRF-Token", $token);
				return caller.onBeforeSend.apply(caller, arguments);
			};

			$ajax.onSuccess = function(response) {
				caller.requestIsSending = false;
				caller.onSuccess.apply(caller, arguments);
			};

			$ajax.onError = function(xhr, ajaxOptions, thrownError) {
				caller.requestIsSending = false;
				caller.onError.apply(caller, arguments);
			};

			$ajax.onComplete = function() {
				caller.requestIsSending = false;
				caller.onComplete.apply(caller, arguments);
			};

			if(!caller.requestIsSending) {
				caller.requestIsSending = true;
				$ajax.sendRequest();
			}
		});
	}
});


/*
 * Loadable JS Class
 * By Marko Prelic
 */
var Loadable = Element.extend({
	ATTRIBUT_URL: "data-url",
	ATTRIBUT_METHOD: "data-method",
	ATTRIBUT_TYPE: "data-type",

	onBeforeAction: function() { return true; },
	onBeforeSend: function(request) {},
	onSuccess: function(response) {},
	onError: function(xhr, ajaxOptions, thrownError) {},
	onComplete: function() {},

	getData: function() {},


	_init: function(element) {
		this._super(element);
		var caller = this;

		var $performAction = caller.onBeforeAction.apply(caller, arguments);
		if($performAction == false) {
			return false;
		}

		var $url = $(element).attr(caller.ATTRIBUT_URL);
		var $method = $(element).attr(caller.ATTRIBUT_METHOD);
		var $type = $(element).attr(caller.ATTRIBUT_TYPE);
		var $data = caller.getData();

		if (!$url) {
			return false;
		}

		if (!$method) {
			$method = "get";
		}

		if (!$type) {
			$type = "json";
		}

		var $ajax = new Ajax($url, $method, $type, $data);

		$ajax.onBeforeSend = function(request) {
			caller.onBeforeSend.apply(caller, arguments);
		};

		$ajax.onSuccess = function(response) {
			caller.onSuccess.apply(caller, arguments);
		};

		$ajax.onError = function(xhr, ajaxOptions, thrownError) {
			caller.onError.apply(caller, arguments);
		};

		$ajax.onComplete = function() {
			caller.onComplete.apply(caller, arguments);
		};

		$ajax.sendRequest();
	}
});


/*
 * RedirectButton JS Class
 * By Marko Prelic
 */
var RedirectButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.click(function(e) {
			e.preventDefault();

			var $url = $(element).attr("data-redirect");
			if($url) {
				window.location = $url;
			}
		});
	}
});


/*
 * PopupNotification JS Class
 * By Marko Prelic
 */
var NotificationMessage = Class.extend({
	_init: function() {
		var $header = $("#NotificationHeader").html();
		var $body = $("#NotificationBody").html();
		var $footer = $("#NotificationFooter").html();

		if($header || $body || $footer) {
			$("#Popup .modal-header").html($header);
			$("#Popup .modal-body").html($body);
			$("#Popup .modal-footer").html($footer);

			$("#Popup").modal("show");
		}
	}
});



function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

