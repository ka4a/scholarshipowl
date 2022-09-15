/*
 * Register3Button JS Class
 * By Branislav Jovanovic
 */
var regPopupLocation;

if (typeof Register3Button !== 'undefined') {
  var Register3LoanButton = Register3Button.extend({

    onBeforeSend: function (request) {
      $(".has-error").removeClass("has-error");
      $(".errorState").remove();

      // Popup can't be opened on onSucces because browser security
      if (window._register3_redirect_popup) {
        regPopupLocation = window.open('', '_blank');
      }
    },

    onSuccess: function (response) {
      if (response.status == "error") {
        errorLoader();
        $.each(response.data, function (k, v) {
          $('[name="' + k + '"]').closest('.form-group')
            .addClass('has-error')
            .append("<div class='errorState clearfix'>" + v + "</div>");
        });

        if (regPopupLocation && regPopupLocation.close) {
          regPopupLocation.close();
          regPopupLocation = null;
        }

      } else if (response.status == "redirect" && response.data) {
        window.location = response.data;

      } else if (response.status == 'redirect_popup' && window._register3_redirect_popup && regPopupLocation) {
        if (response.data && response.data.redirect && response.data.redirect_popup) {
          regPopupLocation.location.href = response.data.redirect;
          window.location = response.data.redirect_popup;
          regPopupLocation.blur();
          window.focus();

        }
      }
    }
  });

  $(function () {
    $(".Register3Button").each(function () {
      $(this).unbind();
      new Register3LoanButton($(this));
    });
  });

  window._register3_redirect_popup = true;
}
