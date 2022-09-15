
/*
 * Mailbox JS Class
 * By Marko Prelic
 */
var Mailbox = Element.extend({
	_init: function() {
		var $caller = this;

        $("#mailbox-folder").change(function() {
            $caller.loadFolder($(this).val());
        });
        this.loadFolder();
	},
    loadFolder:function(folder){
        var $caller = this;
        var $folder = folder || $('#mailbox-folder option:selected').val();
        if($folder) {
            var $action = "/api/v1.0/mailbox/search/" + $folder;
            var $type = "get";
            var $dataType = "json";
            var $list = $(".mail-messages-list");

            var $ajax = new Ajax($action, $type, $dataType);

            $ajax.onBeforeSend = function (request) {
                $list.empty();
                $("#mail-message-sender").empty();
                $("#mail-message-subject").empty();
                $("#mail-message-date").empty();
                $(".mail-message").empty();
            };

            $ajax.onSuccess = function (response) {
                if (response.status == "ok") {
                    var $rows = "";
                    var $order = 1;
                    if (!$.isEmptyObject(response.data)) {
                        var $lastUid = false;
                        $rows += "<div class=\"mail-messages-list\">";

                        for (var $key in response.data) {
	                        var $message = response.data[$key];
	                        var $listTitle = ($folder == "inbox") ? $message.from : $message.to;
	                        $lastUid = $message.uid;

	                        $rows += "<div id=\"message-"+$message.uid+"\" class=\"list-item\" data-order=\""+($order++)+"\"><div class=\"row\">";
	                        $rows += "<div class=\"col-xs-8 col-sm-8\">";
	                        $rows += "<p class=\"text-left text-uppercase\"><a href=\"#\" data-uid=\""+$message.uid+"\" class=\"username loadMessage\">"+$listTitle+"</a></p>";
	                        $rows += "<h3 class=\"subject\">"+$message.subject+"</h3>";
	                        $rows += "<p class=\"mail-teaser\">"+$message.body+" ... </p>";
	                        $rows += "</div>";

	                        $rows += "<div class=\"col-xs-4 col-sm-4\">";
	                        $rows += "<div class=\"text-right\"><span class=\"date\">"+$message.date+"</span></div>"
	                        $rows += "</div>";
	                        $rows += "</div></div>";
	                    }


                        $rows += "</div>";
                        $(".mail-list .mCSB_container").html($rows);


                        //  Init message links
                        $(".loadMessage").click(function(e){
                            e.preventDefault();
                            var $messageId = $(this).attr("data-uid");
                            $caller.loadMessage($messageId);
                        });

                        // Init mailbox sorter
                        $('#mailbox').jplist({
                            itemsBox: '.mail-messages-list'
                            ,itemPath: '.list-item'
                            ,panelPath: '.jplist-panel'
                        });

                        $("button.jplist-drop-down[data-order]").click(function () {
                            $(this).attr('data-order', ($(this).attr('data-order') === 'asc' ? 'desc' : 'asc'))
                        });

                        if($lastUid !== false){
                            $caller.loadMessage($lastUid);
                        }
                    }
                    else {
                    	var $noEmailsMessage = "Currently, there are no emails in your " + $folder + " folder to display. Please check back regularly for updates on your scholarships";
                    	$("#mail-message-body .mail-message").html($noEmailsMessage);
                    }
                }
                else if (response.status == "error") {
                    if (response.data == 3000) {
                        alert("Please select folder !");
                    }
                    else if (response.data == 3001) {
                        alert("Wrong folder !");
                    }
                    else if (response.data == 3002) {
                        alert("Message not selected !");
                    }
                    else {
                        alert("Error occured, please check later !");
                    }
                }
                else if (response.status == "redirect") {
                    window.location = response.data;
                }
            };

            $ajax.onError = function (xhr, ajaxOptions, thrownError) {
            };
            $ajax.onComplete = function () {
            };

            $ajax.sendRequest();
        }
    },
    loadMessage:function(id){
        var $caller = this;
        var $id = id;

			$('#mail-message-body iframe')
				.attr('src', '/mailbox/' + id);

      if($id !== false && $id !== undefined) {
            var $action = "/api/v1.0/mailbox/read/" + $id;
            var $type = "get";
            var $dataType = "json";

            var $ajax = new Ajax($action, $type, $dataType);

            $ajax.onBeforeSend = function (request) {
                $("#mail-message-sender").empty();
                $("#mail-message-subject").empty();
                $("#mail-message-date").empty();
                $(".mail-message").empty();
            };

            $ajax.onSuccess = function (response) {
                if (response.status == "ok") {
                    var $rows = "";
                    var $order = 1;
                    if(!$.isEmptyObject(response.data)){
                        $(".mail-list .list-item").each(function(){
                            $(this).removeClass("active");
                        });
                        var $message = response.data;
                        if($message.folder == "sent"){
                            $("#mail-message-sender").html($message.to);
                        }else{
                            $("#mail-message-sender").html($message.from);
                        }

                        $("#mail-message-subject").html($message.subject);
                        $("#mail-message-date").html($message.date);

                        // var $string = $message.body;
                        //var $text = $caller.strip_tags($string, "<img><a><b>");
                        //$("#mail-message-body .mail-message").html($caller.nl2br($text, true));

                        // $("#mail-message-body .mail-message").html($string);
                        // $("#mail-message-body").mCustomScrollbar("scrollTo", ["top",null]);

                        $("#message-"+$id).addClass("active");
                    }
                } else if (response.status == "error") {
                    if (response.data == 3000) {
                        alert("Please select folder !");
                    }
                    else if (response.data == 3001) {
                        alert("Wrong folder !");
                    }
                    else if (response.data == 3002) {
                        alert("Message not selected !");
                    }
                    else {
                        alert("Error occured, please check later !");
                    }
                }
                else if (response.status == "redirect") {
                    window.location = response.data;
                }
            };

            $ajax.onError = function (xhr, ajaxOptions, thrownError) {
            };
            $ajax.onComplete = function () {
            };

            $ajax.sendRequest();
        }
    },
    nl2br:function(str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    },
    strip_tags:function(input, allowed) {
        allowed = (((allowed || '') + '')
            .toLowerCase()
            .match(/<[a-z][a-z0-9]*>/g) || [])
            .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
            commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return input.replace(commentsAndPhpTags, '')
            .replace(tags, function ($0, $1) {
                return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
            });
    }
});
