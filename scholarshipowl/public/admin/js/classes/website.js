

/* 
 * MailTemplatePreview JS Class
 * By Marko Prelic
 */
var MailTemplatePreview = Element.extend({
	_init: function(element) {
		this._super(element);
		
		element.click(function(e) {
			e.preventDefault();
			
			var $action = "/admin/website/mail-template";
			var $type = "get";
			var $dataType = "json";
			
			var $ajax = new Ajax($action, $type, $dataType);
			
			$ajax.onSuccess = function(response) {
				if(response.status == "ok") {
					var mailWindow = window.open("", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, width=400, height=400");
					mailWindow.document.write(response.data);
				}
			};
			
			$ajax.sendRequest();
		});
	},
});


/* 
 * SaveSettingButton JS Class
 * By Marko Prelic
 */
var SaveSettingButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;
		
		element.bind("click", function(e) {
			e.preventDefault();
			
			var $settingId = element.attr("setting_id");
			var $name = $("#name_" + $settingId).val();
			var $type = $("#type_" + $settingId).val();
			var $isAvailableInRest = $("#isAvailableInRest_" + $settingId).val();

			if ($type == 'array') {
				var $value = $("#value_" + $settingId).val();
			}
			else {
                if($("#value_" + $settingId).is("textarea")) {
                    $("#value_" + $settingId).tinymce().save();
                }
				var $value = $("#value_" + $settingId).val();
			}
			
			if ($name && $type && $value) {
				var $ajax = new Ajax("/admin/website/post-settings", "post", "json", { name: $name, type: $type, value: $value, isAvailableInRest: $isAvailableInRest });
				
				$ajax.onBeforeSend = function() {
					caller.attr("disabled", "disabled");
				};
				
				$ajax.onSuccess = function(response) {
					alert(response.message);
				};
				
				$ajax.onComplete = function() {
					caller.removeAttr("disabled");
				}
				
				$ajax.sendRequest();
			}
			else {
				alert("Please enter a setting value !");
			}
		});
	}
});
