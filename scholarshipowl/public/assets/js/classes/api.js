
/*
 * ApiButton JS Class
 * By Marko Prelic
 */
var ApiButton = AjaxButton.extend({
	onSuccessOk: function(response) {},
	onSuccessError: function(response) {},
	
	onBeforeSend: function(request) {
		// GET TOKEN
	},

	onSuccess: function(response) {
		if(response.status == "ok") {
			window.location = response.data;
		}
		else if(response.status == "error") {
			
		}
		else if(response.status == "redirect") {
			window.location = response.data;
		}
	}
});

