//File upload
$('#fileupload').fileupload({
    dataType: 'json',

    add: function (e, data) {
        data.submit();
    },

    done: function (e, data) {

    },

    progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').show().css(
            'width',
            progress + '%'
        );
    },
    success: function (result, textStatus, jqXHR) {
        var $essayId = $("ul.uploadFileList").attr("data-essayid");
        var $scholarshipId = $("ul.uploadFileList").attr("data-scholarshipid");
        var $html = "";
        var $shouldBeMarked = false;
        for(var i in result.files){
                $html += "<li class='list-group-item' id='" + result.files[i].id + "' data-file-id='" + result.files[i].id + "'>";
                $html += "<div class='nameOfFile'><a class='fileName' target='_blank' href='" + result.files[i].url +"'>" + result.files[i].name +"</a></div>";
                $html += "<span class='deleteFile'>";
                $html += "<a title='delete' class='deleteFileLink' data-delete-id='" + result.files[i].id + "' href='#'><img class='deleteFile' src='/assets/img/fileUpload/deleteFile.png'></a>";
                $html += "<a title='edit' class='editFileLink' id='" + result.files[i].id + "'><img class='editFile' src='/assets/img/fileUpload/editFile.png'></a>";
                $html += "</span>";
                $html +=  "<span class='chooseButtonCont'>";
                $html += "</span>";
                $html += "<div class='clearfix'></div>";
                $html += "</li>";
                if($essayId != undefined && $scholarshipId != undefined){
                    var key = $essayId + '_' + $scholarshipId;
                    if(Window.assignedFiles[key] == undefined){
                        $shouldBeMarked = true;
                        Window.assignedFiles[key] = [];
                        var $fileId = result.files[i].id;
                        var data = {'essay_id': $essayId,'scholarship_id': $scholarshipId, 'file_id': $fileId};
                        $.ajax({
                            url: 'files/attach',
                            data: data,
                            type: 'post',
                            error: function() {
                                alert('An error has occurred');
                            },
                            success: function(data) {
                                Window.assignedFiles[key].push(parseInt($fileId));
                            },
                        });
                    }
                }
        }

        $("#progress .bar").css("background-color", "green");
        $("ul.list-group.uploadFileList").append($html);
        $("ul.list-group.uploadFileList li:last-child").hide().fadeIn(1500);
        $("#progress .bar").hide();
        $("#essay-popup.in li.list-group-item:last-child").each(function() {
            if($shouldBeMarked == false){
                $(this).find("span.chooseButtonCont").html("<button class='AttachFileToEssay' data-fileid='" + this.id + "' data-essayid='" +  $essayId + "' data-scholarshipid='" + $scholarshipId + "'>mark</button>");
            } else {
                $(this).find("span.chooseButtonCont").html("<button class='DetachFileFromEssay' data-fileid='" + this.id + "' data-essayid='" +  $essayId + "' data-scholarshipid='" + $scholarshipId + "'>unmark</button>");
            }
        });

    }
});


$('.uploadFileList').on('click', '.deleteFileLink', function(e) {
    var fileId = $(this).attr('data-delete-id');
    var $url = "files/delete/"+fileId;
    var $type = "post";
    var $data = {'file_id' : fileId};
    $.ajax({
        method: $type,
        url: $url,
        data: $data,
        success: function(data){
            $("li[data-file-id='" + fileId +"']").css("display", "none");
        }
    }).done(function( msg ) {
        if(msg == "false"){
            alert("Somtehnig went wrong");
        }
    });
});

// Edit file name
$('.uploadFileList').on('click', '.editFileLink', function(e) {
    var $getFileNameText = $(".list-group-item[data-file-id=" + this.id +"] .fileName").text().trim();
    $(".list-group-item[data-file-id=" + this.id +"] .fileName").replaceWith("<input class='inputFileDescription' type='text' name='description-"+this.id+"' id='filename-"+this.id+"' value='" + $getFileNameText +"' data-file-id="+this.id+"><input class='uploadFile saveDescription' type='submit' name='submit' data-save-file-id="+this.id+" value='Save'><div class='clearfix'></div> ");
    $(".list-group-item[id=" + this.id +"] .editFile").hide();
    $(".list-group-item[id=" + this.id +"] .deleteFileLink").hide();
    $(".list-group-item[id=" + this.id +"] .nameOfFile").css("float", "none");
    $(".list-group-item[id=" + this.id +"] .chooseButtonCont button").hide();
});


$(document).on('click', '.saveDescription', function() {
    var fileId = $(this).data('save-file-id');
    var fileName = $('#filename-'+fileId).val();
    var $url = "files/edit/"+fileId;
    var $type = "post";
    var $data = {'file_name' : fileName};
    $.ajax({
        method: $type,
        url: $url,
        data: $data
    }).done(function( msg ) {
        if(msg == "false"){
            alert("Somethnig went wrong");
        }
    });
    $("input.inputFileDescription[data-file-id=" + fileId + "]").replaceWith("<a target='_blank' href='files/download/" + fileName  +"' class='fileName'>" + fileName +"</a>");
    $(".uploadFile.saveDescription[data-save-file-id=" + fileId + "]").hide();
    $(".list-group-item[id=" + fileId +"] .editFile").show();
    $(".list-group-item[id=" + fileId +"] .deleteFileLink").show();
    $(".list-group-item[id=" + fileId +"] .nameOfFile").css("float", "left");
    $(".list-group-item[id=" + fileId +"] .chooseButtonCont button").show();
});
// Search files
function filter(element) {
    var $trs = $("ul.uploadFileList li").hide();
    var regexp = new RegExp($(element).val(), 'i');
    var $valid = $trs.filter(function () {
        return regexp.test($(this).find("a.fileName").text());
    }).show();
    $trs.not($valid).hide()
}
$("input#filterFiles").on("keyup change", function () {
    filter(this);
});
