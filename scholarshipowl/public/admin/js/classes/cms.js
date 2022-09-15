

/*
 * SaveCmsForm JS Class
 * By Miodrag Opacic
 */



$(document).ready(function() {
    $('.save-page').click(function(evt) {
        evt.preventDefault();
        var data = $('#SaveCmsForm').serialize();
        var ajax = new Ajax("/admin/cms/post-edit","post","json",data);
        ajax.onSuccess = function(data) {
            if (data.status == "ok") {
                $(caller).parent().parent().remove();
            }
            else if (data.status == "error") {
                alert(data.message);
            } else if (data.status == "redirect") {
                document.location = data.data;
            }
        };
        ajax.sendRequest();
    });
});



