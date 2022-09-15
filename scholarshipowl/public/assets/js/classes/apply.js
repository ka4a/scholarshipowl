/*
 * ApplyTable JS Class
 * By Ivan Krkotic
 */
var ApplyTable = Element.extend({
    _init: function (element) {
        this._super(element);
        var caller = this;

        var $reapply = $("#reapply").val();

        var $action = "/api/v1.0/apply" + (($reapply == 1) ? "?reapply=1" : "");
        var $type = "get";
        var $dataType = "json";
        var $token = $("input[name=_token]").val();

        if (typeof $token === "undefined") {
            throw "Token Not Defined";
        }

        var $ajax = new Ajax($action, $type, $dataType);

        $ajax.onBeforeSend = function (request) {
            // Check token
        };

        $ajax.onError = function (xhr, ajaxOptions, thrownError) {
        };

        $ajax.onSuccess = function(response) {
            if(response.status == "ok") {
                if(!$.isEmptyObject(response.data.scholarships)) {
                    for (var key in response.data.scholarships) {
                        var recurrentIcon = (response.data.scholarships[key].is_recurrent) ?
                            "<span class=\"recurrent-container\"><span class=\"recurrent-icon glyphicon glyphicon-refresh\"></span>" : "";
                        var recurrentClass = (response.data.scholarships[key].is_recurrent) ? "reccurent-scholarship" : "";
                        var tooltipCtrl = (response.data.scholarships[key].is_recurrent) ? "<i class='icon icon-help tooltip-controller tooltip-controller_my-app' aria-hidden='true' data-toggle='tooltip' data-trigger='hover click' data-placement='auto top' title='Recurring scholarships are scholarships which are reinstated periodically (e.g. weekly, monthly, yearly…)'></i>" : "";

                        var rows = "<tr id=\"row" + response.data.scholarships[key].scholarship_id + "\" class=\"clickable\">";

                        // Rendering Table For (Non) Paid Customers
                        rows += "<td data-text=\"" + response.data.scholarships[key].created_date + "\" class=\"mod-td-checkbox\"><input name=\"scholarship_id[]\" type=\"hidden\" value=\"" + response.data.scholarships[key].scholarship_id + "\"><input class=\"ApplyCheckbox\" name=\"apply[]\" type=\"checkbox\" value=\"" + response.data.scholarships[key].scholarship_id + "\"><span class=\"lbl padding-0\"></span></td>";

                        rows += "<td style='width:1%'>" + recurrentIcon + "</strong>" + "</td>";

                        rows += "<td class=\"" + recurrentClass + "\">" + "<strong>" + response.data.scholarships[key].title + tooltipCtrl + "</strong>" + "</td>";

                        rows += "<td class=\"mod-td-toc\"><a href=\"" + ((response.data.scholarships[key].terms_of_service_url != "")?response.data.scholarships[key].terms_of_service_url:response.data.scholarships[key].url) + "\" target=\"_blank\" class=\"text-black\">View T&C</a></td>";

                        //var exp_date = new Date(response.data.scholarships[key].expiration_date);
						//rows += "<td>" + ("0" + exp_date.getDate()).slice(-2) + "/" + ("0" + (exp_date.getMonth() + 1)).slice(-2) + "/" + exp_date.getFullYear() + "</td>";
						//var format_amount = parseFloat(response.data.scholarships[key].amount);
						//rows += "<td><strong>$" + format_amount.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2') + "</strong></td>";
                        var parts = response.data.scholarships[key].expiration_date.split("/");
                        var expDate = parts[2] + parts[0] + parts[1];
                        rows += "<td class=\"hidden-xs hidden-sm mod-td-deadline\" data-text=\"" + expDate + "\">" + response.data.scholarships[key].expiration_date + "</td>";
                        rows += "<td class=\"mod-td-ammount\"><strong>$" + response.data.scholarships[key].amount + "</strong></td>";
                        rows += "</tr>";

                        element.find("tbody").append(rows);
                    }

                    if(!element.tablesorter) {
                        var interval = setInterval(function() {
                            console.log('checking...');
                            if(element.tablesorter) {
                                element.tablesorter({
                                    sortList: [[5, 1]]
                                });
                                clearInterval(interval);
                            }
                        }, 500)
                    } else {
                        element.tablesorter({
                            sortList: [[5, 1]]
                        });
                    }

                    caller.preselect();
                    element.trigger("update");
                    $('#default-sort').addClass('tablesorter-headerAsc');
                    $("[data-toggle=tooltip]").tooltip();

                    $(document).on('shown.bs.tooltip', function(e) {
                        $(e.target).data("bs.tooltip").inState.click = false;
                        $(e.target).data("bs.tooltip").inState.hover = false;
                        $(document).scroll(function() {
                            $(e.target).tooltip('hide');
                        });
                    });

                    $(document).on('hidden.bs.tooltip', function(e) {
                        $(e.target).data("bs.tooltip").inState.click = false;
                        $(e.target).data("bs.tooltip").inState.hover = false;
                    });
                }else{
                    var row = "<div data-error=\"scholarships-limit\" class=\"free text-center\"><span class=\"note\"><strong><big>Currently no scholarship applications are available.</big></strong><br />We add more scholarships every week. Check again soon.</span></div>"
                }
            }

            else if(response.length == 0) {
                $(".empty-apply-table").show();
                $("#info-table").hide();
            }

            else if(response.status == "error") {
                switch(response.data){
                    case -1:
                        alert("System error");
                        break;
                    case 1000:
                        alert("Apply not selected");
                        break;
                    case 1001:
                        alert("Apply payed members only");
                        break;
                    case 1002:
                        alert("Apply no credit");
                        break;
                }
            }
            else if(response.status == "redirect") {
                window.location = response.message;
            }
            // Add a message on select page if there are no scholarships matches
            if(response){
                var numberOfAppse = (response.data.scholarships + '').length;
                if(numberOfAppse == 0) {
                    $(".empty-apply-table").fadeIn("slow");
                    $("#info-table").hide();
                }
            }
        }

        $ajax.sendRequest();
    },
    objSize: function (obj) {
        var count = 0;
        if (typeof obj == "object") {
            if (Object.keys) {
                count = Object.keys(obj).length;
            } else if (window._) {
                count = _.keys(obj).length;
            } else if (window.$) {
                count = $.map(obj, function () {
                    return 1;
                }).length;
            } else {
                for (var key in obj) if (obj.hasOwnProperty(key)) count++;
            }
        }
        return count;
    },
    preselect: function () {
        var caller = this;

        var $pretic = $("input[name=pretick]").val();
        if ($pretic) {
        	if ($pretic == "pretick_all") {
        		$(".ApplyCheckbox").prop('checked', true);
        		$("#selectAll").toggleClass('hidden');
            $("#selectNone").toggleClass('hidden');
        	}
        	else {
        		$(".ApplyCheckbox").slice(0, parseInt($pretic)).prop('checked', true);
                if ($(".ApplyCheckbox").length <= $pretic) {
                    $("#selectAll").toggleClass('hidden');
                    $("#selectNone").toggleClass('hidden');
                }
        	}
        }

        /*
        var $credit = $("#credit").text();
        if ($credit != "") {
            $(".ApplyCheckbox").slice(0, parseInt($credit)).prop('checked', true);
            if ($(".ApplyCheckbox").length <= $credit) {
                $("#selectAll").toggleClass('hidden');
                $("#selectNone").toggleClass('hidden');
            }
        } else if ($(".appNumber").text() == "UNLIMITED") {
            $(".ApplyCheckbox").prop('checked', true);
            $("#selectAll").toggleClass('hidden');
            $("#selectNone").toggleClass('hidden');
        } else {
            if (!$(".appNumber").length)
                setTimeout(caller.preselect, 500);
        }
        */
    }
});

/*
 * ApplyButton JS Class
 * By Marko Prelic
 */
var ApplyButton = Element.extend({
    _init: function (element) {
        this._super(element);
        var caller = this;

        element.click(function (e) {
            e.preventDefault();
            var $selected = $.find("input[name='apply[]']:checked").length;

            if($selected == 0) {
                $("#Popup .modal-body").html("Please select at least one scholarship to apply to before you proceed.");
                $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");
                $("#Popup").modal("show");
                return;
            }

            function isMobile() {
                var check = false;
                (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
                return check;
            }

            if(!document.getElementById('reapply') && !isMobile()) {
                new PaymentPopup($("#payment-popup"), 'select');
                return;
            }

            var $performAction = caller.onBeforeAction.apply(caller, arguments);

            if ($performAction == false) {
                return false;
            }

            var $token = $("input[name=_token]").val();
            if (typeof $token === "undefined") {
                throw "Token Not Defined";
            }

            var $reapply = $("#reapply").val();


            var $action = "/api/v1.0/apply" + (($reapply == 1) ? "?reapply=1" : "");
            var $type = "post";
            var $dataType = "json";
            var $data = new Object();
            var scholarshipIDs = $("input[name='apply[]']:checked").map(function () {
                return $(this).val();
            }).get();
            $data.scholarships = scholarshipIDs;

            $data = JSON.parse(JSON.stringify($data));

            var $ajax = new Ajax($action, $type, $dataType, $data);

            $ajax.onBeforeSend = function (request) {
            };

            $ajax.onSuccess = function (response) {
                caller.onSuccess.apply(caller, arguments);
            };

            $ajax.onError = function(xhr, ajaxOptions, thrownError) {
                caller.onError.apply(caller, arguments);
            };

            $ajax.onComplete = function() {
            };

            $ajax.sendRequest();
        });
    },

    onBeforeAction: function() {
        $(this).find('.btn__loader').show();
    },


    onSuccess: function(response) {
      $(this).find('.btn__loader').hide();

        if (response.status == "ok") {
            var $message = "We have successfully applied you to the selected Scholarships. You will now be redirected to the account page.";
            var $url = response.message;

            $("#Popup .modal-body").html($message);
            $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' href='" + $url + "'>Continue</a>");
            $("#Popup").modal("show");

            setTimeout("window.top.location.href = '" + $url + "';", 10000);
        }
        else if (response.status == "redirect") {
            if (response.data == "my-applications") {
                var $message = "Great Job. Answer the essay questions on the next page to complete your applications."
                var $url = response.data;

                $("#Popup .modal-body").html($message);
                $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' href='" + $url + "'>Continue</a>");
                $("#Popup").modal("show");

                setTimeout("window.top.location.href = '" + $url + "';", 10000);
            }

            window.top.location.href = response.data;
        } else if(response.status == "error") {
            if(response.data == 1001 || response.data == 1002){
              if (this.$this.attr('data-url')) {
                window.location.href = this.$this.attr('data-url');
              } else {
                $("#payment-popup").addClass("apply-after");
                new PaymentPopup($("#payment-popup"));
              }
            }else {
                $("#Popup .modal-body").html(response.message);
                $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");
                $("#Popup").modal("show");
            }
        }
    },
    onError: function() {
      $(this).find('.btn__loader').hide();
    }
});

/*
 * ApplyPageButton JS Class
 * By Marko Prelic
 */
var ApplyPageButton = Class.extend({
	_init: function(selector) {
		$(selector).click(function(e) {
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                e.preventDefault();
                new PaymentPopup($("#payment-popup"), $(selector).attr("data-page"));
            }
			
		});
	},
});
