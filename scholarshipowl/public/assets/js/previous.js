
/*
 * EssayForm JS Class
 * By Marko Prelic
 */
var EssayForm = Element.extend({
    _init: function(element) {
        this._super(element);
        var caller = this;
        var $isSaved = true;
        var $leaving = $('#LeavingModal');
        var $isSelect = false;
        $leaving.on('hidden.bs.modal', function (e) {
            $isSelect = false;
        });

        var $token = $("input[name=_token]").val();
        if(typeof $token === "undefined") {
            throw "Token Not Defined";
        }

        var $essaysCount = $(this).attr("data-essays-count");
        if ($essaysCount <= 0) {
            $("#Popup .modal-body").html("None of the applications you selected require an essay.");
            $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' href='my-account'>Ok</a>");
            $("#Popup").modal("show");
        }

        var $select = new Element("#EssaySelect");
        $select.bind("change", function(e) {
            e.preventDefault();
            var $essayId = $(this).val();
            if (!$isSaved) {
                $isSelect = true;
                $leaving.modal("show").one("click", ".LeaveButton", function() {
                    isExit = false;
                    if ($essayId) {
                        var $ajax = new Ajax("/post-essay", "post", "json", {essayId: $essayId});

                        $ajax.onBeforeSend = function (request) {
                            request.setRequestHeader("X-CSRF-Token", $token);

                            $("#EssayText").val("");
                            $("#EssayDescription").html("");
                            $("#EssayTextParsed").html("");
                            $("#EssayRequirements").html("");
                            $("#EssayRequirementsValues input[type=hidden]").val("");
                            $("#ScholarshipData").html("");
                        };

                        $ajax.onSuccess = function (response) {
                            if (response.status == "ok") {
                                var $data = response.data;

                                $("#EssayText").val($data.text);
                                $("#EssayDescription").html($data.description);

                                $leaving.modal("hide");
                                $isSaved = true;

                                initEditing($data.text);
                                initRequirements($data.minWords, $data.maxWords, $data.minCharacters, $data.maxCharacters);
                                initScholarship($data.scholarshipTitle, $data.scholarshipAmount);
                            }
                        };

                        $ajax.sendRequest();
                    }
                });
            }else {
                if ($essayId) {
                    var $ajax = new Ajax("/post-essay", "post", "json", {essayId: $essayId});

                    $ajax.onBeforeSend = function (request) {
                        request.setRequestHeader("X-CSRF-Token", $token);

                        $("#EssayText").val("");
                        $("#EssayDescription").html("");
                        $("#EssayTextParsed").html("");
                        $("#EssayRequirements").html("");
                        $("#EssayRequirementsValues input[type=hidden]").val("");
                        $("#ScholarshipData").html("");
                    };

                    $ajax.onSuccess = function (response) {
                        if (response.status == "ok") {
                            var $data = response.data;

                            $("#EssayText").val($data.text);
                            $("#EssayDescription").html($data.description);

                            initEditing($data.text);
                            initRequirements($data.minWords, $data.maxWords, $data.minCharacters, $data.maxCharacters);
                            initScholarship($data.scholarshipTitle, $data.scholarshipAmount);
                        }
                    };

                    $ajax.sendRequest();
                }
            }
        });

        if($("#EssaySelect").val() == '0') {
            $("#EssaySelect option:eq(1)").attr("selected", "selected");
        }
        $("#EssaySelect").change();


        var $text = new Element("#EssayText");
        $text.bind("input propertychange", function(e) {
            e.preventDefault();
            initEditing($(this).val());
        });

        var $oldValue = $("#EssayText").val();

        $text.bind("propertychange change click keyup input paste", function(e){
            if ($oldValue != $(this).val() && $(this).val() != '' && $isSaved) {
                $oldValue = $(this).val();
                $isSaved = false;
            }
        });

        var $save = new Element(".EssaySaveButton");
        $(".EssaySaveButton").bind("click", function(e) {
            e.preventDefault();

            if(!$(this).hasClass("NotEditable")) {
                var $token = $("input[name=_token]").val();
                if(typeof $token === "undefined") {
                    throw "Token Not Defined";
                }

                var $essayId = $("#EssaySelect").val();
                var $text = $("#EssayText").val();

                if($essayId && $text) {
                    var $ajax = new Ajax("/post-save-essay", "post", "json", { essayId: $essayId, text: $text });

                    $ajax.onBeforeSend = function(request) {
                        request.setRequestHeader("X-CSRF-Token", $token);
                    };

                    $ajax.onSuccess = function(response) {
                        $("#EssaysSaved").html(response.data.saved);
                        $("#EssaysSavedAmount").html(response.data.savedAmount);

                        $isSaved = true;
                        $oldValue = $("#EssayText").val();

                        $("#Popup .modal-body").html(response.message);
                        $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");
                        $("#Popup").modal("show");
                    };

                    $ajax.sendRequest();
                }
            }
        });

        var $done = new Element(".EssaysDone");
        $done.bind("click", function(e) {
            e.preventDefault();
            if (!$isSaved) {
                $leaving.modal("show").one("click", ".LeaveButton", function () {
                    isExit = false;
                    if(!$isSelect) {
                        window.location = $(this).attr("href");
                    }
                });
            } else {
                window.location = $(this).attr("href");
            }
        });

        var initEditing = function(text) {
            var $result = assertText(text);
            var $parsed = parseText(text);

            if($result) {
                $(".EssaySaveButton").removeAttr("disabled").removeClass("NotEditable");
            }
            else {
                $(".EssaySaveButton").attr("disabled", "disabled").addClass("NotEditable");
            }

            if($parsed) {
                $("#EssayTextParsed").html("Words: " + $parsed.words + " | Characters: " + $parsed.characters);
            }
        };

        var initRequirements = function(minWords, maxWords, minCharacters, maxCharacters) {
            if(minWords > 0) {
                $("#EssayRequirements").append("Minimum Words: " + minWords + "<br>");
                $("input[type=hidden][name=EssayMinWords]").val(minWords);
            }

            if(maxWords > 0) {
                $("#EssayRequirements").append("Maximum Words: " + maxWords + "<br>");
                $("input[type=hidden][name=EssayMaxWords]").val(maxWords);
            }

            if(minCharacters > 0) {
                $("#EssayRequirements").append("Minimum Characters: " + minCharacters + "<br>");
                $("input[type=hidden][name=EssayMinCharacters]").val(minCharacters);
            }

            if(maxCharacters > 0) {
                $("#EssayRequirements").append("Maximum Characters: " + maxCharacters + "<br>");
                $("input[type=hidden][name=EssayMaxCharacters]").val(maxCharacters);
            }
        };

        var initScholarship = function(scholarshipTitle, scholarshipAmount) {
            $("#ScholarshipData").html("This essay is for " + scholarshipTitle + " worth <span class='number'>$" + scholarshipAmount + "</span>");
            $.fn.digits = function(){
                return this.each(function(){
                    $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
                })
            };
            $("span.number").digits();
        };

        var assertText = function(text) {
            if(!text) {
                return false;
            }

            var $result = true;
            var $parsed = parseText(text);

            var $minWords = $("input[type=hidden][name=EssayMinWords]").val();
            var $maxWords = $("input[type=hidden][name=EssayMaxWords]").val();
            var $minCharacters = $("input[type=hidden][name=EssayMinCharacters]").val();
            var $maxCharacters = $("input[type=hidden][name=EssayMaxCharacters]").val();

            if($minWords && $parsed.words < $minWords) {
                $result = false;
            }

            if($maxWords && $parsed.words > $maxWords) {
                $result = false;
            }

            if($minCharacters && $parsed.characters < $minCharacters) {
                $result = false;
            }

            if($maxCharacters && $parsed.characters > $maxCharacters) {
                $result = false;
            }

            return $result;
        };

        var parseText = function(text) {
            var $result = {
                words: 0,
                characters: 0
            };

            if(text.length == 0) {
                return $result;
            }

            $temp = text.replace(/(^\s*)|(\s*$)/gi,"");
            $temp = $temp.replace(/[ ]{2,}/gi," ");
            $temp = $temp.replace(/\n /,"\n");

            $result.words = $temp.split(' ').length;
            $result.characters = $temp.length;

            return $result;
        };
    }
});

/*
 * NoEssaysButton JS Class
 * By Marko Prelic
 */
var NoEssaysButton = Element.extend({
    _init: function(element) {
        this._super(element);

        element.bind("click", function(e) {
            e.preventDefault();

            var $essaysCount = $(this).attr("data-essays-count");
            if ($essaysCount > 0) {
                $(this).attr("href", "essays");
                window.location = "essays";
            }
            else {
                $("#Popup .modal-body").html("None of the applications you selected require an essay.");
                $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");
                $("#Popup").modal("show");
            }
        });
    }
});

/*
 * ApplyCheckbox JS Class
 * By Marko Prelic
 */
var ApplyCheckbox = Element.extend({
    _init: function(element) {
        this._super(element);

        element.bind("change", function(e) {
            e.preventDefault();

            var $isFree = $("input[name=is_free][type=hidden]").val();
            var $credit = $("input[name=credit][type=hidden]").val();
            var $selected = $.find("input[name='apply[]']:checked").length;
            var $value = $(element).is(":checked");

            if ($selected > $credit && $value == true && !$isFree) {
                //  Removed opening payment popup when too many scholarships are selected
            }
        });
    }
});