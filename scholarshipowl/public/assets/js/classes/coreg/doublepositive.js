var DoublePositiveButton = AjaxButton.extend({
    onBeforeSend: function(request) {
        $(".has-error").removeClass("has-error");
        $(".errorState").remove();
    },

    onSuccess: function(response) {
        if(response.status == "error") {
            errorLoader();
            $.each(response.data, function(k,v) {
                $('[name="'+k+'"]').closest('.form-group').addClass('has-error').append("<div class='errorState clearfix'>" + v + "</div>");
            });
        }
        else if(response.status == "redirect") {
            preventReLoad();
            window.location = response.data;
        }
    }
});

$(function() {
    $(".DoublePositiveButton").each(function () {
        new DoublePositiveButton($(this));
    });
});