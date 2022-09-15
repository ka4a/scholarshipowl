/*
 * AddAffiliateGoalButton JS Class
 * By Marko Prelic
 */
var AddAffiliateGoalButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $affiliateGoalId = $(caller).attr("data-affiliate-goal-id");
			var $affiliateGoalFullName = $(caller).attr("data-affiliate-goal-full-name");


			if ($affiliateGoalId) {
				var $exists = $("input[type=text][name=affiliate_goal_" + $affiliateGoalId + "_name");
				if ($exists.length) {
					alert("Affiliate goal already added !");
					return false;
				}

				var $html = "";
				var $timestamp = Date.now();

				$html += "<tr id=\"" + $affiliateGoalId + "_sorting\">";
				$html += "<td><input type='hidden' name='affiliate_goal_" + $affiliateGoalId + "_type' value='1' class='form-control' /> Affiliate</td>";
				$html += "<td><input type='text' name='affiliate_goal_" + $affiliateGoalId + "_name' value='" + $affiliateGoalFullName + "' class='form-control' /></td>";
				$html += "<td><input type='text' size='3' name='affiliate_goal_" + $affiliateGoalId + "_points' value='100' class='form-control' /></td>";
				$html += "<td></td>";
				$html += "<td><input type='checkbox' name='affiliate_goal_" + $affiliateGoalId + "_active' value='1' /></td>";
				$html += "<td><a href='#' class='btn btn-danger DeleteAffiliateGoalButton' data-timestamp='" + $timestamp + "' data-mission-goal-id=''>Delete</a></td>";
				$html += "</tr>";

				$("#MissionGoalsTable tbody").append($html);
				$(".DeleteAffiliateGoalButton[data-timestamp=" + $timestamp + "]").each(function() {
					new DeleteAffiliateGoalButton($(this));
				});

				$(".modal").modal("hide");
			}

			return false;
		});
	}
});


/*
 * DeleteAffiliateGoalButton JS Class
 * By Marko Prelic
 */
var DeleteAffiliateGoalButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $missionGoalId = $(caller).parent().parent().attr("data-mission-goal-id");

			if ($missionGoalId) {
				var $ajax = new Ajax("/admin/missions/delete-goal/" + $missionGoalId);
				$ajax.onSuccess = function(data) {
					if (data.status == "ok") {
						$(caller).parent().parent().remove();
					}
					else if (data.status == "error") {
						alert("Error deleting mission goal");
					}
				};
				$ajax.sendRequest();
			}
			else {
				$(caller).parent().parent().remove();
			}

			return false;
		});
	}
});



/*
 * AddReferralAwardButton JS Class
 * By Marko Prelic
 */
var AddReferralAwardButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $referralAwardId = $(caller).attr("data-referral-award-id");
			var $referralAwardName = $(caller).attr("data-referral-award-name");


			if ($referralAwardId) {
				var $exists = $("input[type=text][name=referral_award_" + $referralAwardId + "_name");
				if ($exists.length) {
					alert("Referral award already added !");
					return false;
				}

				var $html = "";
				var $timestamp = Date.now();

				$html += "<tr id=\"" + $referralAwardId + "_sorting\">";
                $html += "<td><input type='hidden' name='affiliate_goal_" + $referralAwardId + "_type' value='2' class='form-control' /> Refer A Friend</td>";
				$html += "<td><input type='text' name='referral_award_" + $referralAwardId + "_name' value='" + $referralAwardName + "' class='form-control' /></td>";
				$html += "<td><input type='text' size='3' name='referral_award_" + $referralAwardId + "_points' value='100' class='form-control' /></td>";
				$html += "<td></td>";
				$html += "<td><input type='checkbox' name='referral_award_" + $referralAwardId + "_active' value='1' /></td>";
				$html += "<td><a href='#' class='btn btn-danger DeleteReferralAwardButton' data-timestamp='" + $timestamp + "' data-mission-goal-id=''>Delete</a></td>";
				$html += "</tr>";

				$("#MissionGoalsTable tbody").append($html);
				$(".DeleteReferralAwardButton[data-timestamp=" + $timestamp + "]").each(function() {
					new DeleteReferralAwardButton($(this));
				});

				$(".modal").modal("hide");
			}

			return false;
		});
	}
});


/*
 * DeleteReferralAwardButton JS Class
 * By Marko Prelic
 */
var DeleteReferralAwardButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $missionGoalId = $(caller).parent().parent().attr("data-mission-goal-id");

			if ($missionGoalId) {
				var $ajax = new Ajax("/admin/missions/delete-goal/" + $missionGoalId);
				$ajax.onSuccess = function(data) {
					if (data.status == "ok") {
						$(caller).parent().parent().remove();
					}
					else if (data.status == "error") {
						alert("Error deleting mission goal");
					}
				};
				$ajax.sendRequest();
			}
			else {
				$(caller).parent().parent().remove();
			}

			return false;
		});
	}
});

/*
*   AddAdButton Class
*   By Ivan Krkotic
 */
var AddAdButton = Element.extend({
    _init: function(element) {
        this._super(element);
        var caller = this;

        this.bind("click", function(e) {
            e.preventDefault();

            var $html = "";
            var $timestamp = Date.now();

            $html += "<tr id=\"" + $timestamp + "\">";
            $html += "<td><input type='hidden' name='ad_type_" + $timestamp + "' value='3' class='form-control' /> Advertisement</td>";
            $html += "<td><input type='text' name='ad_name_" + $timestamp + "' value='Name' class='form-control' /></td>";
            $html += "<td></td>";
            $html += "<td><input type='text' name='ad_parameters_" + $timestamp + "' value='' class='form-control' /></td>";
            $html += "<td><input type='checkbox' name='ad_active_" + $timestamp + "' value='1' /></td>";
            $html += "<td><a href='#' class='btn btn-danger DeleteAdButton' data-timestamp='" + $timestamp + "' data-mission-goal-id=''>Delete</a></td>";
            $html += "</tr>";

            $("#MissionGoalsTable tbody").append($html);
            $(".DeleteAdButton[data-timestamp=" + $timestamp + "]").each(function() {
                new DeleteAdButton($(this));
            });

            $(".modal").modal("hide");
        });
    }
});

/*
 * DeleteAdButton JS Class
 * By Ivan Krkotic
 */
var DeleteAdButton = Element.extend({
    _init: function(element) {
        this._super(element);
        var caller = this;

        this.bind("click", function(e) {
            e.preventDefault();

            var $missionGoalId = $(caller).parent().parent().attr("data-mission-goal-id");

            if ($missionGoalId) {
                var $ajax = new Ajax("/admin/missions/delete-goal/" + $missionGoalId);
                $ajax.onSuccess = function(data) {
                    if (data.status == "ok") {
                        $(caller).parent().parent().remove();
                    }
                    else if (data.status == "error") {
                        alert("Error deleting mission goal");
                    }
                };
                $ajax.sendRequest();
            }
            else {
                $(caller).parent().parent().remove();
            }

            return false;
        });
    }
});