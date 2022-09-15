/**
 * PaymentPopup JS Class
 * By Ivan Krkotic
 */
var PaymentPopup = Element.extend({
    _init: function (element, page, callerElement) {
        this._super(element);
        var caller = this;

        if (element.length) {
            if (window.ga && ga.create) {
                ga("send", "event", "payment", "popupLoaded", {"page": "/".page});
            }
            var show0 = element.data("show-zero") == 1;

            element.one('show.bs.modal', function () {
                window.SOWLMixpanelTrack('PaymentPopup open');
            });
            element.one('hidden.bs.modal', function () {
                window.SOWLMixpanelTrack('PaymentPopup closed');
            });

            element.on("show.bs.modal", function () {
                var heights = $('#packages')
                    .find('.selectButton')
                    .map(function () {
                        return $(this).actual('outerHeight')
                    })
                    .get();

                $('.selectButton').css('height', Math.max.apply(null, heights));

                if (show0 == false) {
                    $("#payment-wizard a:first").tab("show");
                } else {
                    $("#payment-wizard a:first").tab("show");
                    $(".tab-pane#step0").addClass("active in")
                }

                $('#launcher').css({'display': 'none'});

            });
            element.modal("show");
            element.on("hidden.bs.modal", function () {
                if ($(".PopupDisplay").length) {
                    $(".PopupDisplay").each(function () {
                        if (($(this).data("popup-display") == 2 || $(this).data("popup-display") == 3) && $("#popupActionCompleted").val() == 0) {
                            new PopupDisplay($(this), $(this).data("popup-delay"), true);
                        }
                    });
                }
                $("#payment-popup").removeData("prev");
                if ($("#payment-popup").hasClass("apply-after") && $("#popupActionCompleted").val() == 1) {
                    $(".ApplyButton").click();
                }
                if (show0 == false) {
                    $("#payment-wizard a:first").tab("show");
                    $("#payment-wizard a:first").next().tab("show");
                    $(".tab-pane#step1").addClass("active");
                    $('#step1').removeClass('fade');
                } else {
                    $("#payment-wizard a:first").tab("show");
                }
                clearInterval($globalRefreshInterval);
                $('#launcher').css({'display': 'block'});
            });

            var $backToBeginning = new Element(".backToBeginning");
            $backToBeginning.bind("click", function (e) {
                e.preventDefault();
                if ($("#payment-popup").data("prev")) {
                    $("[href=#" + $("#payment-popup").data("prev").pop() + "]").tab('show');
                    if ($("#payment-popup").data("prev") === undefined || $("#payment-popup").data("prev").length == 0) {
                        $("#payment-popup").removeData("prev");
                    }
                } else {
                    $("#payment-wizard a[href='#step1']").tab("show");
                }
                $("#payment-popup #payment-wizard").removeClass("resetWidth");
                clearInterval($globalRefreshInterval);
                if (window.zE !== undefined) zE.hide();
            });

            $(".continueButton").click(function () {
                $(".nav-pills li:nth-child(2) a").trigger("click");
            });

        }
    }
});

/*
 * PackageButton JS Class
 * By Marko Prelic
 */
var PackageButton = Element.extend({

    _init: function (element) {
        this._super(element);

        var heights = $('.package')
            .find('.selectButton')
            .map(function () {
                return $(this).actual('outerHeight')
            })
            .get();

        $('.selectButton').css('height', Math.max.apply(null, heights));

        var that = this, returnUrl = $("#payment-popup").find("input[type=hidden][name=return]").val();
        $('.payment-packages').find('.PackageButton').attr('data-hide-back-button', 'true');
        /**
         * TODO: Move this logic to payment popup.
         * @type {{showCCButton: Function, hideCCButton: Function, toggleCCButton: Function}}
         */
        var paymentPopupStep2 = {

            hideBack: function ($paymentPopup) {
                $paymentPopup.find('#previous').attr('data-dismiss', 'modal');
            },

            showBack: function ($paymentPopup) {
                $paymentPopup.find('#previous').removeAttr('data-dismiss')
            },

            showCCButton: function ($paymentPopup) {
                $paymentPopup.find('.PaymentFormButton').removeClass('hidden');
                $paymentPopup.find('.img-circle.or').removeClass('hidden');
                $paymentPopup.find('input.paypal-input').removeClass('paypal-cc');
                $paymentPopup.find('span.paypal-container').removeClass('pp-cc');
            },

            hideCCButton: function ($paymentPopup) {
                $paymentPopup.find('input.paypal-input').addClass('paypal-cc');
                $paymentPopup.find('span.paypal-container').addClass('pp-cc');
                $paymentPopup.find('.PaymentFormButton').addClass('hidden');
                $paymentPopup.find('.img-circle.or').addClass('hidden');
            },

            toggleCCButton: function ($paymentPopup, flag) {
                flag = typeof flag === 'undefined' ? true : !!flag;

                if (flag) {
                    this.showCCButton($paymentPopup);
                } else {
                    this.hideCCButton($paymentPopup);
                }
            }

        };

        element.click(function (e) {
            e.preventDefault();

            var $this = $(this),
                $paymentPopup = $('#payment-popup'),
                CCPaymentEnabled = $this.attr('data-package-cc-recurrent') === 'on';

            var $accountId = $("#payment-popup").find("input[type=hidden][name=account_id]").val();
            var $packageId = $(this).attr("data-package-id");
            var contactUsLink = $(this).attr("data-contact-us-link");
            var $packageName = $(this).attr("data-package-name");
            var $packagePrice = $(this).attr("data-package-price");
            var $billingAgreement = $(this).attr("data-package-billing-agreement");
            var $trackingParams = $(this).attr("data-tracking-params");
            var $freeTrial = $(this).attr('data-package-free-trial') === 'true';
            var packageType = $this.attr('data-package-type');

            window.SOWLMixpanelTrack('PackageButton click', {
                'id': $packageId,
                'name': $packageName,
                'price': $packagePrice
            });

            if (packageType && packageType.toLowerCase() === 'contact us') {
                elitPackage($packageId, contactUsLink, $paymentPopup);
                return;
            }

            var fillCheckoutPackage = function () {
                if (window.SOWLCheckout) {
                    window.SOWLCheckout.setPackageId($packageId);
                    window.SOWLCheckout.setBillingAgreement($billingAgreement);
                    window.SOWLCheckout.setTrackingParams($trackingParams);
                    window.SOWLCheckout.setFreeTrial($freeTrial);
                    window.SOWLCheckout.clearErrorMessage();
                }
            };

            if (window.SOWLCheckout) {
                fillCheckoutPackage();
            } else {
                console.log('SOWLCHeckout not loaded');
                setTimeout(fillCheckoutPackage, 1000);
            }

            $("#payment-popup").find(".PackageOptions").addClass("hidden");
            $("#payment-popup").find(".PackageOptions[data-package-id=" + $packageId + "]").removeClass("hidden");
            $("#payment-popup").find("#PackageOptionsContainer").removeClass("hidden");

            $("#payment-popup").find("#Gate2ShopForm").html("");
            $("#payment-popup").find("input[type=hidden][name=item_name]").val($packageName);
            $("#payment-popup").find("input[type=hidden][name=amount]").val($packagePrice);
            $("#payment-popup").find("input[type=hidden][name=custom]").val($packageId + "_" + $accountId + "_" + $trackingParams);

            if ($this.attr('data-package-expiration') === 'recurrent') {
                $paymentPopup.find('input[type=hidden][name=cmd]').val('_xclick-subscriptions');
                $paymentPopup.find('input[type=hidden][name=a3]').val($packagePrice);
                $paymentPopup.find('input[type=hidden][name=p3]').val($this.attr('data-package-period-duration'));
                $paymentPopup.find('input[type=hidden][name=t3]').val($this.attr('data-package-period-type'));
                $paymentPopup.find('input[type=hidden][name=no_note]').val('1');

                paymentPopupStep2.toggleCCButton($paymentPopup, CCPaymentEnabled);
            } else {
                $paymentPopup.find('input[type=hidden][name=cmd]').val('_xclick');
                $paymentPopup.find('input[type=hidden][name=no_note]').val('0');

                paymentPopupStep2.showCCButton($paymentPopup);
            }

            if ($this.attr('data-hide-back-button') == 'true') {
                paymentPopupStep2.hideBack($paymentPopup);
            }
            else {
                paymentPopupStep2.showBack($paymentPopup);
            }

            $("#payment-popup").find(".PaymentFormButton").attr("data-package-id", $packageId);
            $("#payment-popup").find(".PaymentFormButton").attr("data-tracking-params", $trackingParams);
            $("#payment-popup").find("input[type=hidden][name=return]").val(returnUrl + "?package_id=" + $packageId + "&account_id=" + $accountId + "&trackingParams=" + $trackingParams);

            if ($("#payment-popup").data("prev")) {
                var $prevStates = $("#payment-popup").data("prev");
                $prevStates.push($("#payment-popup").find(".tab-pane.active").attr("id"));
                $("#payment-popup").data("prev", $prevStates);
            } else {
                $("#payment-popup").data("prev", [$("#payment-popup").find(".tab-pane.active").attr("id")]);
            }

            if (($("#payment-popup").data("bs.modal") || {isShown: false}).isShown) {
                var nextId = $(this).parents(".tab-pane").next().attr("id");
                $("[href=#" + nextId + "]").tab("show");

                setTimeout(function () {
                    if ($("#step2").hasClass("active")) {
                        if (window.zE !== undefined) zE.activate();
                    };
                }, 180 * 1000);
            } else {
                $(".modal.in").modal("hide");
                $("#payment-popup").modal("show");
                var nextId = $("#payment-popup").find(".tab-pane").next().attr("id");
                $("[href=#" + nextId + "]").tab("show");
            }

            // add focuse credit card input
            var input = $("[title='Credit Card Number']");
            if(input.length) {
                setTimeout(function() {
                    input.focus()
                }, 700)
            }

            $("#payment-popup #payment-wizard").addClass("resetWidth");
            return false;
        });
    }
});


/*
 * PaymentFormButton JS Class
 * By Marko Prelic
 */
var PaymentFormButton = Element.extend({
    _init: function (element) {
        this._super(element);

        element.click(function (e) {
            var $packageId = $(this).attr("data-package-id");
            var $trackingParams = $(this).attr("data-tracking-params");

            var $url = "/payment-form?packageId=" + $packageId + "&trackingParams=" + $trackingParams;

            var $html = "<iframe class='center-block' id='payment-form' name='payment_form' src='" + $url + "' scrolling='auto' width='100%' height='100%' marginwidth='0' marginheight='0' frameborder='0' vspace='0' hspace='0'></iframe>";
            $("#Gate2ShopForm").html($html);

            $("html, body").animate({
                scrollTop: $("#Gate2ShopForm").offset().top
            }, 1000);

            var nextId = $(this).parents(".tab-pane").next().attr("id");
            if ($("#payment-popup").data("prev")) {
                var $prevStates = $("#payment-popup").data("prev");
                $prevStates.push($("#payment-popup").find(".tab-pane.active").attr("id"));
                $("#payment-popup").data("prev", $prevStates);
            } else {
                $("#payment-popup").data("prev", [$("#payment-popup").find(".tab-pane.active").attr("id")]);
            }
            $("[href=#" + nextId + "]").tab('show');
            return false;
        });
    }
});


/*
 * MissingPaymentDataButton JS Class
 * By Marko Prelic
 */
var MissingPaymentDataButton = Element.extend({
    _init: function (element) {
        this._super(element);

        element.click(function (e) {
            $("#Popup .modal-body").html("Please go to your account and fill all required data for payment.");
            $("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");
            $("#Popup").modal("show");
        });
    }
});

/*
*   ExploreMoreButton JS Class
*   Author: Ivan Krkotic
 */
var ExploreMoreButton = Element.extend({
    _init: function (element) {
        this._super(element);
        element.click(function (e) {
            e.preventDefault();
            $("#packages").find(".package.collapse").show(500).css("visibility", "visible");
        });
    }
});

/*
 *   MissionButton JS Class
 *   Author: Ivan Krkotic
 */
var MissionButton = Element.extend({
    _init: function (element) {
        this._super(element);
        element.click(function (e) {
            e.preventDefault();

            var $missionId = $(this).attr("data-mission-id");
            var $missionOptions = $(".MissionOptions[data-mission-id=" + $missionId + "]");
            var $missionOptionsDiv = $missionOptions.find(".MissionOptionsDiv");
            var $missionGoalsDiv = new MissionGoalsDiv($missionOptionsDiv);
            return false;
        });
    }
});

/*
 * MissionGoalsDiv JS Class
 * By Ivan Krkotic
 */
var MissionGoalsDiv = Loadable.extend({
    onSuccess: function (response) {
        var $missionId = $globalMissionId = $(this).attr("data-mission-id");
        var $div = $(this);
        $div.empty();

        $.each(response.data, function (index, value) {
            if (value.type == 1) {
                var $goalId = value.affiliate_goal.affiliate_goal_id;

                var $html = "<div class=\"col-xs-12 survey-box startCountDown\" data-goal-id=\"" + $goalId + "\"><div class=\"survey" + ((parseInt(value.is_accomplished) == 1) ? " survey-active" : "") + "\" data-goal-id=\"" + value.mission_goal_id + "\">";
                $html += "<div class=\"survey-logo text-center\"><a href=\"#\" data-goal-id=\"" + $goalId + "\"><img src=\"" + value.affiliate_goal.logo + "\"></a></div>";
                $html += "<p>" + ((typeof(value.affiliate_goal.description) == "string" && value.affiliate_goal.description != "") ? value.affiliate_goal.description : value.name) + "</p>";
                $html += "<div class=\"survey-button text-center\">" + ((parseInt(value.is_accomplished) == 1) ? "<span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>" : (parseInt(value.is_started) == 1) ? "<a class=\"text-uppercase\">continue</a>" : "<a class=\"text-uppercase\">start</a>") + "</div>";
                $html += "</div></div>";

                $div.append($html);

            }

            if (value.type == 2) {
                var $goalId = value.referral_award.referral_award_id;
                var $goalDescription = value.referral_award.description;
                var $redirectDescription = value.referral_award.description;
                var $html = "<div class=\"col-xs-12 survey-box ReferralMissionButton\" data-goal-id=\"" + $goalId + "\" data-goal-description=\"" + encodeURIComponent($redirectDescription) + "\"><div class=\"survey" + ((parseInt(value.is_accomplished) == 1) ? " survey-active" : "") + "\">";
                $html += "<div class=\"row text-center\"><div class=\"col-xs-3 social-widget\"><img class=\"img-responsive\" src=\"/assets/img/refer-icon-facebook.png\" alt=\"Facebook\"></div><div class=\"col-xs-3 social-twitter social-widget\"><img class=\"img-responsive\" src=\"/assets/img/refer-icon-twitter.png\" alt=\"Twitter\"></div><div class=\"col-xs-3 social-widget\"><img class=\"img-responsive\" src=\"/assets/img/refer-icon-pinterest.png\" alt=\"Pinterest\"></div><div class=\"col-xs-3 social-widget last\"><img class=\"img-responsive\" src=\"/assets/img/refer-icon-mail.png\" alt=\"Mail\"></div></div>";
                $html += "<p class=\"text-light\">" + $goalDescription + "</p>";

                $html += "<div class=\"survey-button text-center\">" + ((parseInt(value.is_accomplished) == 1) ? "<span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>" : (parseInt(value.is_started) == 1) ? "<a class=\"text-uppercase\">continue</a>" : "<a class=\"text-uppercase\">start</a>") + "</div>";

                $html += "</div></div>";

                //var $html = $("#raf-surveys-popup .modal-item").html();
                //$div.append("<div class='colx-xs-6 col-sm-4 survey-box'><div class='survey'" + $html + "</div></div>");
                $div.append($html);
            }

            if (value.type == 3) {
                var $html = "<div class=\"col-xs-12 survey-box\"><div class=\"advertTitle\"></div><div class=\"survey adsGoalBox\"><div class=\"advertTitle\">advertisement</div> " + value.html + "</div></div>";
                $div.append($html);
                var $dom = $.parseHTML(value.html);
            }
        });

        if (response.data.length == 1) {
            $("#payment-mission-direct").css("max-width", "330px");
            $("#package-exit-popup").find(".modal-dialog").css("max-width", "330px");
        }

        if (response.data.length == 2) {
            $("#payment-mission-direct").css("max-width", "610px")
            $("#package-exit-popup").find(".modal-dialog").css("max-width", "610px");
        }

        if (response.data.length > 2) {
            $("#package-exit-popup").find(".modal-dialog").css("max-width", "888px");
        }

        $("#package-exit-popup").find(".modal-dialog").animate({top: ""}, 500);

        $(".startCountDown").each(function () {
            new StartMissionButton($(this));
        });

        $(".ReferralMissionButton").each(function () {
            new ReferralMissionButton($(this));
        });

        if ($("#payment-popup").data("prev")) {
            var $prevStates = $("#payment-popup").data("prev");
            $prevStates.push($("#payment-popup").find(".tab-pane.active").attr("id"));
            $("#payment-popup").data("prev", $prevStates);
        } else {
            $("#payment-popup").data("prev", [$("#payment-popup").find(".tab-pane.active").attr("id")]);
        }

        if ($("#payment-popup").hasClass("in")) {
            var $nextId = "missionTabPayment";
            $("[href=#" + $nextId + "]").tab("show");
        } else {
            $("[href=#missionTab]").tab('show');
        }

        $(".MissionOptions").addClass("hidden");
        $(".MissionOptions[data-mission-id=" + $missionId + "]").removeClass("hidden");
        $("#MissionOptionsContainer").removeClass("hidden");
        $globalRefreshInterval = setInterval(refreshMission, 5000);
    }
});

/*
 * Mission Start/Continue Button
 * By Ivan Krkotic
 */
var StartMissionButton = Element.extend({
    _init: function (element) {
        this._super(element);
        element.click(function (e) {
            var $goalId = $(this).attr("data-goal-id");
            window.open("/goal/" + $goalId);
        });
    }
});

/*
 * Complete Mission Button
 * By Ivan Krkotic
 */
var CompleteMissionButton = Element.extend({
    _init: function (element) {
        this._super(element);
        element.click(function (e) {
            var $missionId = $(this).attr("data-mission-id");
            $("[href=#missionCongratulationsTab]").tab('show');
            $(".MissionCongratulations").addClass("hidden");
            $(".MissionCongratulations[data-mission-id=" + $missionId + "]").removeClass("hidden");

            var $action = "/api/v1.0/missions/notify";
            var $type = "post";
            var $dataType = "json";
            var $data = JSON.parse(JSON.stringify({"missionId": $missionId}));

            var $ajax = new Ajax($action, $type, $dataType, $data);

            $ajax.onSuccess = function (response) {
                element.attr("disabled", "disabled");
                element.text("Completed");

            }

            $ajax.sendRequest();
        });
    }
});

/*
 * Referral Mission Button
 * By Ivan Krkotic
 */
var ReferralMissionButton = Element.extend({
    _init: function (element) {
        this._super(element);
        element.click(function (e) {
            var $goalId = $(this).attr("data-goal-id");
            var $goalDescription = $(this).attr("data-goal-description");

            $("#rafMissionTab #offerText").html(decodeURIComponent($goalDescription));

            var $action = "/api/v1.0/missions/refer-a-friend/" + $goalId;
            var $type = "post";
            var $dataType = "json";

            var $ajax = new Ajax($action, $type, $dataType);

            $ajax.sendRequest();
            if ($("#payment-popup").data("prev")) {
                var $prevStates = $("#payment-popup").data("prev");
                $prevStates.push($("#payment-popup").find(".tab-pane.active").attr("id"));
                $("#payment-popup").data("prev", $prevStates);
            } else {
                $("#payment-popup").data("prev", [$("#payment-popup").find(".tab-pane.active").attr("id")]);
            }
            if (($("#payment-popup").data("bs.modal") || {isShown: false}).isShown) {
                $("[href=#rafMissionTab]").tab('show');
            } else {
                $("[href=#missionExitRafTab]").tab('show');
            }
        });
    }
});

var $globalMissionId, $globalRefreshInterval;

function openTab(url) {
    if(!url) return;

    if(!/^(ftp|http|https):\/\/[^ "]+$/.test(url)) {
        console.log('Elite redirect link not valid!');
        return;
    }

    var tab = window.open(url, '_blank');

    if(!tab || !tab.focus) alert('Please allow pop-up for this site. You will be redirected to ' + url + ' page');

    tab.focus();
}

function contactUsRequest(packageId, resolve, reject) {
    $.get('/rest/v1/notification/email/send/' + packageId)
        .done(resolve).fail(reject);
}

function elitPackage(packageId, contactUsLink, paymentPopup) {
    contactUsRequest(packageId, function(response) {
        if(!response) return;

        if(contactUsLink) {
            openTab(contactUsLink);
            return;
        }

        if(paymentPopup) paymentPopup.modal('hide');

        vueModal.show('Elite', {
            title: response.success_title,
            message: response.success_message
        })

    }, function(response) {
        vueModal.show('Elite', {
            title: 'Uhh!',
            message: 'Something went wrong. Please try in 5 minutes'
        })
    });
}

function refreshMission() {
    var $action = "/api/v1.0/missions/status/" + $globalMissionId;
    var $type = "get";
    var $dataType = "json";
    $("#popupActionCompleted").val(0);

    var $ajax = new Ajax($action, $type, $dataType, null, false);
    $ajax.onSuccess = function (response) {
        if (response.status == "ok") {
            if (response.data.mission_status == "completed") {
                $("#popupActionCompleted").val(1);
                clearInterval($globalRefreshInterval);
                var $missionId = $globalMissionId;
                $(".MissionCongratulations").addClass("hidden");
                $(".MissionCongratulations[data-mission-id=" + $missionId + "]").removeClass("hidden");
                setTimeout(function () {
                    $("[href=#missionCongratulationsTab]").tab('show');
                }, 2000)


                var $action = "/api/v1.0/missions/notify";
                var $type = "post";
                var $dataType = "json";
                var $data = JSON.parse(JSON.stringify({"missionId": $missionId}));

                var $ajax = new Ajax($action, $type, $dataType, $data);

                $ajax.sendRequest();
            }
            if (response.data.goals) {
                $.each(response.data.goals, function (index, value) {
                    if (parseInt(value.is_accomplished) == 1) {
                        $(".survey[data-goal-id=" + index + "]").addClass("survey-active");
                        $(".survey[data-goal-id=" + index + "]").find(".survey-button").html("<span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>");
                    }
                });
            }
        }
    }

    $ajax.sendRequest();
}

function displayMissionCompletePopup(element) {
    var $missionId = element.data("mission-id");

    $("#payment-popup").on("hidden.bs.modal", function () {
        $("#payment-wizard a:first").tab("show");
    });


    $("[href=#missionCongratulationsTab]").tab('show');
    $(".MissionCongratulations").addClass("hidden");
    $(".MissionCongratulations[data-mission-id=" + $missionId + "]").removeClass("hidden");

    var $action = "/api/v1.0/missions/notify";
    var $type = "post";
    var $dataType = "json";
    var $data = JSON.parse(JSON.stringify({"missionId": $missionId}));

    var $ajax = new Ajax($action, $type, $dataType, $data);

    $ajax.sendRequest();

    $("#payment-popup").modal("show");
}

function initExitPopup(element) {

    function ConfirmLeave() {
        new PopupDisplay(element, element.data("popup-delay"), true);
        return element.data("ext-dialogue-text");
    }
    window.onbeforeunload = ConfirmLeave;
}

//Calculate height of whatYouGet elements in payment form
function whatYouGetHeight() {
    var maxWhatYouGetHeight = 0;
    var maxSelectedPackageHeight = 0;
    $(".whatYouGet").each(function () {
        if ($(this).height() > maxWhatYouGetHeight) {
            maxWhatYouGetHeight = $(this).height();
        }
    });
    $(".selected-package").css({
        "height": maxWhatYouGetHeight + 10,
        "padding-left": "70px",
        "display": "table-cell",
        "vertical-align": "middle",
        "padding-top": "5px",
        "padding-bottom": "5px"
    });
};

$(function () {

    var moveCheckout = (function () {
        var current;

        return function ($container, key) {
            if (typeof key !== 'undefined' && current === key)
                return false;

            if (typeof window.SOWLCheckoutHtml === 'undefined')
                return false;

            current = key;

            $('.sowl-checkout').remove();
            $container.append($(window.SOWLCheckoutHtml).unwrap());

            return true;
        }
    })();

    if(location.pathname.indexOf('upgrade-mobile') !== -1) {
        var $freemiumButton = $('.GetFreemiumButton');
        bindFreemiumHandler($freemiumButton);
    }

    $('a.payment-opener').click(function () {

        var $this = $(this), that = this,
            key = parseInt($this.attr('data-id')),
            $container = $('#has-id-' + key),
            packageType = $this.attr('data-package-type');

        var $packageId = $this.attr("data-package-id");
        var $packageName = $this.attr("data-package-name");
        var $packagePrice = $this.attr("data-package-price");

        window.SOWLMixpanelTrack('PackageButtonMobile click', {
            'id': $packageId,
            'name': $packageName,
            'price': $packagePrice
        });

        if (packageType && packageType.toLowerCase() === 'contact us') {
            elitPackage($this.attr('data-package-id'), $this.attr('data-contact-us-link'))
            return;
        }

        if (moveCheckout($container, key)) {
            $('.checkout-container').hide();
            var $checkout = $container.find('.sowl-checkout');
            window.SOWLCheckout = new SOWLElementCheckout($checkout);
            window.SOWLCheckout.setPackageId($this.attr('data-package-id'));
            window.SOWLCheckout.setBillingAgreement($this.attr('data-package-billing-agreement'));
            window.SOWLCheckout.setTrackingParams($this.attr('data-tracking-params'));
            window.SOWLCheckout.setFreeTrial($this.attr('data-package-free-trial') === 'true');
            window.SOWLCheckout.clearErrorMessage();
            $container.slideToggle(1000);
            $('body, html').animate({scrollTop: $container.offset().top}, 1000);
        }
    });

    //Reload mailbox on Upgrade via Mission
    $("a[href='#missionCongratulationsTab']").on("hidden.bs.tab", function () {
        if (window.location.pathname == "/mailbox") {
            location.reload();
        }
    });

    $("a.ExploreMoreButton").click(function () {
        $(".ExploreMoreButton").hide(500);
    });

    $(".nav-pills li a[data-step='2']").one('shown.bs.tab', function (e) {
        whatYouGetHeight()
    });

    $("#payment-popup").on("hide.modal.bs", function () {
        if ($("#step2").hasClass("active")) {
            if ($("#launcher").hasClass("zEWidget-launcher--active") && window.zE
                && window.zE.activate && typeof window.zE.activate === "function") {
                zE.activate();
            }
        } else if(window.zE && window.zE.show && typeof window.zE.show === "function") {
            zE.show();
        }

        $("#step0").remove();
    });

    // How to behave Exit Popup if it contains less then 3 packages
    $("#package-exit-popup").on("show.bs.modal", function () {
        $("#payment-popup").modal("hide");
        if ($("#missionExitStep2").find(".PackageDiv").length == 1) {
            $(this).find(".modal-dialog").css("max-width", "330px");
            $(this).find(".PackageDiv").css({
                "width": "100%",
                "margin": "0 auto",
                "margin-bottom": "60px",
                "float": "none"
            });
        }
        ;
    });

    // remove padding whenever modal's been closed
    $(".modal").on("hidden.bs.modal", function () {
        $("body").css("padding-right", "0");
    });
});
