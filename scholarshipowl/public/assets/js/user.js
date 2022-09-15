$(document).ready(function() {
	$(".save-changes").click(function(e) {
		e.preventDefault();

		var $token = $("input[name=_token]").val();
		var $form = $(this).closest("form");
		var $data = $form.serialize();

		$.ajax({
			type: "POST",
			url: $form.attr("action"),
			dataType: "JSON",
			beforeSend: function(request) {
				request.setRequestHeader("X-CSRF-Token", $token);
			},
			data: $data,
			success: function(ret) {
				if (ret.success) {
					//console.log(ret);
					$("input[type=text]").removeClass("error")
					$("bootstrap-select").removeClass("error")
					$(".form-element div.error").html("")

					$("#alertMsg").html('<div data-alertid="alert"><a data-dismiss="alert" class="close" href="#"><span>Close</span> ×</a><p id="messageText">Your changes have been saved! Click <a href="apply?from=profile"><u>here</u></a> to apply for more scholarships now.</p>')
					$("#myPageAlert").removeClass("hidden")
					$("#myPageAlert").css("display", "block")
					$("#myPageAlert").show()
					$("#saveModal").html('<div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"></div><div class="modal-body">.test..</div><div class="modal-footer"><button type="button" class="btn btn-warning btn-block">Apply Now</button><button type="button" class="btn btn-primary btn-block">Continue completing profile</button></div></div></div></div>')

				} else {
					$("input[type=text]").removeClass("error")
					$("bootstrap-select").removeClass("error")
					$(".form-element div.error").html("")

					$.each(ret.errors, function(k,v) {
						alert(v);
						//$("[name=" + k +"]").addClass("error").closest(".form-element").find("div.error").html(v)
					})
				}

				if(ret.percentage) {
					$("#userProgressBar").attr("aria-valuenow", ret.percentage);
					$("#userProgressBar").css("width", ret.percentage + "%");
					$("#userProgressBarText").text(ret.percentage + "%");
				}
			},
			fail: function(ret) {
				//console.log(ret);
			}
		});
		//$(this).closest("form").submit()
		//return false;
	});

	$("#myTab a").click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});


	// Phone Input Mask
	if($("#myAccountBasicForm").length) {
		$("[name=phone]").inputmask("(999) 999 - 9999", { "placeholder": "(   )     -     ", showMaskOnHover: false });
	}

});
