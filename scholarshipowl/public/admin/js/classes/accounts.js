/*
 * AddPackageButton JS Class
 * By Branislav Jovanovic
 */
var AddPackageButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.click(function(e) {
			e.preventDefault();

			var $token = $("input[name=_token]").val();
			if(typeof $token === "undefined") {
				throw "Token Not Defined";
			}

			var $accountId = $(this).attr('data-account-id');
			var $packageId = $(this).attr('data-package-id');

			if($accountId && $packageId) {
				var $ajax = new Ajax("/admin/accounts/post-add-subscription", "post", "json", { accountId: $accountId, packageId: $packageId });

				$ajax.onBeforeSend = function(request) {
					request.setRequestHeader("X-CSRF-Token", $token);
				};

				$ajax.onSuccess = function(response) {
					console.log('Success');
					if(response.status == "redirect") {
						window.location.href = window.location.href;
					};
				}

				$ajax.onError = function(xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}

				$ajax.sendRequest();

			}
		});
	},
});


/* 
 * DeleteAccountButton JS Class
 * By Marko Prelic
 */
var DeleteAccountButton = Element.extend({
	_init: function(element) {
		this._super(element);
		
		element.bind("click", function(e) {
			e.preventDefault();
			
			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");
			
			if(confirm($message)) {
				window.location = $url;
			}
		});
	},
});


/* 
 * DeleteMailboxMessageButton JS Class
 * By Marko Prelic
 */
var DeleteMailboxMessageButton = Element.extend({
	_init: function(element) {
		this._super(element);
		
		element.bind("click", function(e) {
			e.preventDefault();
			
			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");
			
			if(confirm($message)) {
				window.location = $url;
			}
		});
	},
});
