
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
            if($("#MissionGoalsTable tbody").length) {
                var $sorting =  $("#MissionGoalsTable tbody").sortable('toArray').toString();
                $data = $data + "&sorting=" + $sorting;
            }
            var $action = $form.attr("action");
			var $type = "post";
			var $dataType = "json";

			var $ajax = new Ajax($action, $type, $dataType, $data);

			$ajax.onBeforeSend = function(request) {
				request.setRequestHeader("X-CSRF-Token", $token);
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
		});
	},
});



/*
 * SaveButton JS Class
 * By Marko Prelic
 */
var SaveButton = AjaxButton.extend({
    onBeforeAction: function(request) {
        $(".tinymce").each(function(){
            var $taId = $(this).attr('id');
            var $elem = $("#"+$taId);

            $elem.html($("#"+$taId).tinymce().getContent());
        });
    },
	onBeforeSend: function(request) {
		$(this.getSelector()).attr("disabled", "disabled");

		$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
		$("#msgNotification").text("");
		$("#msgNotification").hide();

		$(".help-block").remove();
		$(".has-error").removeClass("has-error");
	},

	onSuccess: function(response) {
		if(response.status == "ok") {
			if(response.message) {
				$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
				$("#msgNotification").addClass("bg-common bg-success");
				$("#msgNotification").text(response.message);
				$("#msgNotification").show();
			}
		}
		else if(response.status == "error") {
			if(response.message) {
				$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
				$("#msgNotification").addClass("bg-common bg-danger");
				$("#msgNotification").text(response.message);
				$("#msgNotification").show();
			}

			$.each(response.data, function(k,v) {
				var $errorElement = "<small class='help-block col-sm-offset-3 col-sm-9'>" + v + "</small>";

				$("[name=" + k + "]").parent().parent().addClass("has-error");
				$("[name=" + k + "]").parent().parent().append($errorElement);
			});
		}
		else if(response.status == "redirect") {
			window.location = response.data;
		}
	},

	onComplete: function() {
		$(this.getSelector()).removeAttr("disabled");
		$("#msgNotification").delay(6000).fadeOut(500);
		$("html, body").animate({ scrollTop: 0 }, "slow");
	},
});



/*
 * DeleteButton JS Class
 * By Marko Prelic
 */
var DeleteButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.click(function(e) {
			e.preventDefault();

			var $url = element.attr("data-delete-url");
			var $message = element.attr("data-delete-message");

			if(confirm($message)) {
				var $ajax = new Ajax($url, "get", "json");

				$ajax.onSuccess = function(response) {
					if(response.status == "ok") {

					}
					else if(response.status == "error") {
						alert(response.message);
					}
					else if(response.status == "redirect") {
						window.location = response.data;
					}
				};

				$ajax.sendRequest();
			}
		});
	},
});


/*
 * LoginButton JS Class
 * By Marko Prelic
 */
var LoginButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.click(function(e) {
			e.preventDefault();

			var $token = $("input[name=_token]").val();
			if(typeof $token === "undefined") {
				throw "Token Not Defined";
			}

			$(this).addClass('loader-btn');

			var $form = $(this).closest("form");
			var $data = $form.serialize();
			var $action = $form.attr("action");
			var $type = "post";
			var $dataType = "json";

			var $ajax = new Ajax($action, $type, $dataType, $data);

			$ajax.onBeforeSend = function(request) {
                $('#login-error').hide();
				element.attr("disabled", "disabled");
				request.setRequestHeader("X-CSRF-Token", $token);
			};

			$ajax.onSuccess = function(response) {
				if(response.status == "redirect") {
					if(response.data) {
						window.location = response.data;
					}
				}
				if(response.error !== ""){
                    $('#login-error').show();
                    $('#login-error').text(response.error);
				}
			};

			$ajax.onComplete = function() {
				element.removeAttr("disabled");
				element.removeClass("loader-btn");
			};

			$ajax.sendRequest();
		});
	},
});


/*
 * NotificableElement JS Class
 * By Marko Prelic
 */
var NotificableElement = Element.extend({
	_init: function(element) {
		this._super(element);

		var $type = $(element).attr("data-notification-type");
		var $message = $(element).attr("data-notification-message");

		if ($type && $message) {
			if ($type == "success") {
				$("#msgNotification").addClass("bg-common bg-success");
			}
			else if ($type == "error") {
				$("#msgNotification").addClass("bg-common bg-danger");
			}
			else if ($type == "warning") {
				$("#msgNotification").addClass("bg-common bg-warning");
			}

			$("#msgNotification").text($message);
			$("#msgNotification").show();
		}
	},
});

$('.remove-illustration').click(function () {
	var $illustrationContainer = $(this).parents('.illustration-container');
	$illustrationContainer.find('.illustration-img').attr('src', '');
	$illustrationContainer.find('.register_illustration-flag').attr('checked','checked');
});
