/*
 * LoginButton JS Class
 * By Marko Prelic
 */
var LoginButton = AjaxButton.extend({
    onBeforeSend: function(request) {
        $("#LoginErrorEmail").hide();
        $("#LoginErrorPassword").hide();
    },

    onSuccess: function(response) {
        if(response.status == "redirect") {
            window.location = response.data;
        }
        else if(response.status == "error") {
            if(response.data.email) {
                $("#LoginErrorEmail").html(response.data.email);
                $("#LoginErrorEmail").show();
            }

            if(response.data.password) {
                $("#LoginErrorPassword").html(response.data.password);
                $("#LoginErrorPassword").show();
            }
        }
    }
});

/*
 * ForgotPasswordButton JS Class
 * By Ivan Krkotic
 */
var ForgotPasswordButton = AjaxButton.extend({
    onBeforeSend: function(request) {
        $("#ForgotPasswordErrorEmail").hide();
    },

    onSuccess: function(response) {
        if(response.status == "ok") {
            $("#forgot-password-form").html(response.message);
        }
        else if(response.status == "error") {
            if(response.data.email) {
                $("#ForgotPasswordErrorEmail").html(response.data.email);
                $("#ForgotPasswordErrorEmail").show();
            }
        }
    }
});

/*
 * ResetPasswordButton JS Class
 * By Ivan Krkotic
 */
var ResetPasswordButton = AjaxButton.extend({
    onBeforeSend: function(request) {
        $("#ResetPasswordErrorEmail").hide();
        $("#ResetPasswordErrorPassword").hide();
        $("#ResetPasswordErrorToken").hide();
    },

    onSuccess: function(response) {
        if(response.status == "ok") {
            var $continueUrl = response.data.url;

            $("#Popup .modal-body").html(response.message);
            $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' href='" + $continueUrl + "'>Continue</a>");

            $("#Popup").modal("show");
        }
        else if(response.status == "error") {
            if(response.data.email) {
                $("#ResetPasswordErrorEmail").html(response.data.email);
                $("#ResetPasswordErrorEmail").show();
            }
            if(response.data.password) {
                $("#ResetPasswordErrorPassword").html(response.data.password);
                $("#ResetPasswordErrorPassword").show();
            }
            if(response.data.token) {
                $("#ResetPasswordErrorToken").html(response.data.token);
                $("#ResetPasswordErrorToken").show();
            }
        }
    }
});

$(function() {
  // Show Login Dialog If Redirect Is Defined
  if ($("#LoginFormModal").length) {
      var $loginRedirect = $("input[type=hidden][name=login_redirect]").val();
      
      if ($loginRedirect && window.location.pathname != "/awards/you-deserve-it-scholarship") {
          $("#LoginFormModal").modal("show");
      }
  }
});
