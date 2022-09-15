/**
 * Created by Ivan Krkotic on 6/4/2016.
 */

/*
 * DaneMediaButton JS Class
 * By Ivan Krkoitc
 */
var DaneMediaButton = AjaxButton.extend({
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

/*
 * LastDegreeCompleted JS Class
 * By Ivan Krkotic
 */
var LastDegreeCompleted = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * Campus JS Class
 * By Ivan Krkotic
 */
var Campus = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * RnLicence JS Class
 * By Ivan Krkotic
 */
var RnLicence = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * BsNursing JS Class
 * By Ivan Krkotic
 */
var BsNursing = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * EnrollPercentage JS Class
 * By Ivan Krkotic
 */
var EnrollPercentage = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * HowDedicated JS Class
 * By Ivan Krkotic
 */
var HowDedicated = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * ComputerAccess JS Class
 * By Ivan Krkotic
 */
var ComputerAccess = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

/*
 * StartDate JS Class
 * By Ivan Krkotic
 */
var StartDate = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("change", function(e) {
				e.preventDefault();
				loadPrograms();
			});
		}
	}
});

var loadPrograms = function(){
	var $data = new Array();

	$data["dane_media_campaign_id"] = $("#form_id").length?$("#form_id").val():null;
	$data["last_degree_completed"] = $("#edulevelid").length?$("#edulevelid").val():null;
	$data["campus"] = $("#campus").length?$("#campus").val():null;
	$data["computer_access"] = $("#custom_computer").length?$("#computer_access").val():null;
	$data["rn_licence"] = $("#custom_rn").length?$("#custom_rn").val():null;
	$data["bs_nursing"] = $("#bs_nursing").length?$("#bs_nursing").val():null;
	$data["enroll_percentage"] = $("#enroll_percentage").length?$("#enroll_percentage").val():null;
	$data["how_dedicated"] = $("#how_dedicated").length?$("#how_dedicated").val():null;
	$data["start_date"] = $("#start_date").length?$("#start_date").val():null;
	var obj = $.extend({}, $data);

	$.ajax({
		type: "POST",
		url: "/dane-media-programs",
		data: obj,
		success: function(response){
			var $options = new Array();
			$.each(response.data, function(k,v) {
				$options.push($('<option>').val(v.submission_value).text(v.display_value));
			});
			$("#program").empty();

			$("#program").append($options);
			$("#program").selectpicker("refresh");
		}
	});
}

$(function() {
    $(".DaneMediaButton").each(function() {
        new DaneMediaButton($(this));
    });

    $("#edulevelid").each(function() {
        new LastDegreeCompleted($(this));
    });

    $("#campus").each(function() {
        new Campus($(this));
    });

    $("#custom_computer").each(function() {
        new ComputerAccess($(this));
    });

    $("#custom_rn").each(function() {
        new RnLicence($(this));
    });

    $("#bs_nursing").each(function() {
        new BsNursing($(this));
    });

    $("#enroll_percentage").each(function() {
        new EnrollPercentage($(this));
    });

    $("#how_dedicated").each(function() {
        new HowDedicated($(this));
    });

    $("#start_date").each(function() {
        new StartDate($(this));
    });

	loadPrograms();
	$(".selectpicker").selectpicker();
})