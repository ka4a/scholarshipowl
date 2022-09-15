/*
 * Main Function
 * By Marko Prelic
 */
$(document).ready(function () {

  function copyTextToClipboard(text) {
    var textArea = document.createElement("textarea");

    //
    // *** This styling is an extra step which is likely not required. ***
    //
    // Why is it here? To ensure:
    // 1. the element is able to have focus and selection.
    // 2. if element was to flash render it has minimal visual impact.
    // 3. less flakyness with selection and copying which **might** occur if
    //    the textarea element is not visible.
    //
    // The likelihood is the element won't even render, not even a flash,
    // so some of these are just precautions. However in IE the element
    // is visible whilst the popup box asking the user for permission for
    // the web page to copy to the clipboard.
    //

    // Place in top-left corner of screen regardless of scroll position.
    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;

    // Ensure it has a small width and height. Setting to 1px / 1em
    // doesn't work as this gives a negative w/h on some browsers.
    textArea.style.width = '2em';
    textArea.style.height = '2em';

    // We don't need padding, reducing the size if it does flash render.
    textArea.style.padding = 0;

    // Clean up any borders.
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';

    // Avoid flash of white box if rendered for any reason.
    textArea.style.background = 'transparent';


    textArea.value = text;

    document.body.appendChild(textArea);

    textArea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg, text);
    } catch (err) {
      console.log('Oops, unable to copy');
    }

    document.body.removeChild(textArea);
  }

  $('.subscription-info').each(function(e){
    var $this = $(this);

    $this.find('.btn-edit').click(function(e) {
      e.preventDefault();
      $(this).hide();
      $this.find('input,select').prop('disabled', false);
      $this.find('.btn-save').show();
    });

    $this.find('.btn-cancel-subscription').click(function(e) {
      e.preventDefault();
      var cancelUrl = $(this).attr("href");
      var subscriptionId = cancelUrl.match(/subscriptions\/(\d*)\//)[1];
      if (window.confirm("You are about to cancel subscription "+subscriptionId)) {
		  window.location.href = cancelUrl;
      }
    });
  });

  $('.tags-control .tag').click(function() {
    copyTextToClipboard($(this).text());
  });

  $('[data-toggle="tooltip"]').each(function() {
    $(this).tooltip();
  });

  $('#logs-admin-activity').each(function() {
    new AdminActivityLogDataTable($(this));
  });

  $('#subscriptions-index-page').each(function() {
    new SubscriptionsIndexPage($(this));
  });

  $('.scholarships-requirements-form').each(function(){
    new ScholarshipsRequirementsForm($(this));
  });

  // Statistics daily managment
  $('.conversion-graph').each(function() {
    new StatisticsConversionGraph($(this));
  });

  // common.js
	$(".SaveButton").each(function() {
		new SaveButton($(this));
	});

	$(".DeleteButton").each(function() {
		new DeleteButton($(this));
	});

    $('a[data-confirm-message]').click(function(e) {
        if (!confirm($(this).attr('data-confirm-message'))) {
            e.preventDefault();
        }
    });

	$("#LoginButton").each(function() {
		new LoginButton($(this));
	});

	$(".NotificableElement").each(function() {
		new NotificableElement($(this));
	});


	// scholarships.js
	$("#SaveScholarshipForm").each(function() {
		new SaveScholarshipForm($(this));
	});

	$("#FetchScholarshipFieldsButton").each(function() {
		new FetchScholarshipFieldsButton($(this));
	});

	$(".DeleteScholarshipFieldButton").each(function() {
		new DeleteScholarshipFieldButton($(this));
	});

	$(".DeleteScholarshipButton").each(function() {
		new DeleteScholarshipButton($(this));
	});

	$(".SaveScholarshipInformation").each(function() {
		new SaveScholarshipInformation($(this));
	});

	$("#AddEssayButton").each(function() {
		new AddEssayButton($(this));
	});

	$(".DeleteEssayButton").each(function() {
		new DeleteEssayButton($(this));
	});

	$("#AddEligibilityButton").each(function() {
		new AddEligibilityButton($(this));
	});

	$(".DeleteEligibilityButton").each(function() {
		new DeleteEligibilityButton($(this));
	});

	$(".OnlineApplicationFetchFormButton").each(function() {
		new OnlineApplicationFetchFormButton($(this));
	});

	$(".OnlineApplicationFormFieldButton").each(function() {
		new OnlineApplicationFormFieldButton($(this));
	});

	$("#OnlineApplicationSaveFieldButton").each(function() {
		new OnlineApplicationSaveFieldButton($(this));
	});

	$("#OnlineApplicationDeleteFieldButton").each(function() {
		new OnlineApplicationDeleteFieldButton($(this));
	});


	// packages.js
	$("#SavePackageForm").each(function() {
		new SavePackageForm($(this));
	});

    // popup.js
    $("#SavePopupForm").each(function() {
        new SavePopupForm($(this));
    });

    $(".DeletePopupButton").each(function() {
        new DeletePopupButton($(this));
    });


	// website.js
	$(".MailTemplatePreview").each(function() {
		new MailTemplatePreview($(this));
	});

	$(".SaveSettingButton").each(function() {
		new SaveSettingButton($(this));
	});


	// account.js
	$('.AddPackageButton').each(function() {
		new AddPackageButton($(this));
	});

	$('.DeleteAccountButton').each(function() {
		new DeleteAccountButton($(this));
	});

	$('.DeleteMailboxMessageButton').each(function() {
		new DeleteMailboxMessageButton($(this));
	});


	// missions.js
	$(".AddAffiliateGoalButton").each(function() {
		new AddAffiliateGoalButton($(this));
	});

	$(".DeleteAffiliateGoalButton").each(function() {
		new DeleteAffiliateGoalButton($(this));
	});

	$(".AddReferralAwardButton").each(function() {
		new AddReferralAwardButton($(this));
	});

	$(".DeleteReferralAwardButton").each(function() {
		new DeleteReferralAwardButton($(this));
	});

    $(".AddAdButton").each(function() {
        new AddAdButton($(this));
    });

    $(".DeleteAdButton").each(function() {
        new DeleteAdButton($(this));
    });

	//	marketing.js

	$('.SaveCoregPlugin').each(function() {
        new SaveCoregPlugin($(this));
    });

	$(".DeleteAffiliateGoalMappingButton").each(function() {
		new DeleteAffiliateGoalMappingButton($(this));
	});

	$(".DeleteRulesSetButton").each(function() {
		new DeleteRulesSetButton($(this));
	});

	$(".AddRedirectRuleButton").each(function() {
		new AddRedirectRuleButton($(this));
	});

	$(".AddCoregRequirements").each(function() {
		new AddCoregRequirements($(this));
	});

	$(".DeleteRedirectRuleButton").each(function() {
		new DeleteRedirectRuleButton($(this));
	});

	$(".DeleteCoregPluginButton").each(function() {
		new DeleteCoregPluginButton($(this));
	});

	$(".DeleteMailchimpListButton").each(function() {
		new DeleteMailchimpListButton($(this));
	});

	$(".DeleteTransactionalEmailButton").each(function() {
		new DeleteTransactionalEmailButton($(this));
	});

	// referrals.js
	$("#SaveReferralAwardForm").each(function() {
		new SaveReferralAwardForm($(this));
	});

    $('#permissions-form').each(function() {
        new AclPermissionsForm($(this));
    });

    $('#remove-scholarship-logo').each(function() {
        new RemoveScholarshipLogoButton($(this));
    });

  $('.banners-edit').each(function() {
    new BannersEdit($(this));
  })

	var i = $('#option-list .row').length;
	$('#add-option').click(function () {
		i++;
		$('#option-list').append('<div class="option-row" style="margin-bottom: 6px;"><div class="row">\n' +
			'\t<div class="col-xs-3">\n' +
			'\t\t<textarea id="text-'+i+'" class="form-control tinymce" name="package_common_option['+i+'][text]" cols="50" rows="10"></textarea>\n' +
			'\t</div>\n' +
			'<div class="col-xs-2">Enable in package 1\n' +
			'\t<input id="package_common_option_0" class="hidden" checked="checked" name="package_common_option['+i+'][status][0]" type="checkbox" value="0">\n' +
			'\t<input id="package_common_option_0" name="package_common_option['+i+'][status][0]" type="checkbox" value="1">\n' +
			'\t</div>\n' +
			'\t<div class="col-xs-2">Enable in package 2\n' +
			'\t<input id="package_common_option_1" class="hidden" checked="checked" name="package_common_option['+i+'][status][1]" type="checkbox" value="0">\n' +
			'\t<input id="package_common_option_1" name="package_common_option['+i+'][status][1]" type="checkbox" value="1">\n' +
			'\t</div>\n' +
			'\t<div class="col-xs-2">Enable in package 3\n' +
			'\t<input id="package_common_option_2" class="hidden" checked="checked" name="package_common_option['+i+'][status][2]" type="checkbox" value="0">\n' +
			'\t<input id="package_common_option_2" name="package_common_option['+i+'][status][2]" type="checkbox" value="1">\n' +
			'\t</div>\n' +
			'\t<div class="col-xs-2">Enable in package 4\n' +
			'\t<input id="package_common_option_3" class="hidden" checked="checked" name="package_common_option['+i+'][status][3]" type="checkbox" value="0">\n' +
			'\t<input id="package_common_option_3" name="package_common_option['+i+'][status][3]" type="checkbox" value="1">\n' +
			'\t</div>\n' +
			'</div>' +
			'<div class="row"><input type="button" class="remove-option btn btn-danger" style="margin-left: 15px; margin-top: 5px;" value="Remove"></div>' +
			'</div>');

		tinymce.init({
			forced_root_block: false,
			remove_trailing_brs: false,
			selector: '#text-'+i,
			menubar : false,
			verify_html: false,
			plugins: "code",
			toolbar: "undo redo | styleselect fontsizeselect | bold italic | link | bullist numlist | code",
			style_formats: [
				{title: "Headers", items: [
						{title: "Header 1", format: "h1"},
						{title: "Header 2", format: "h2"},
						{title: "Header 3", format: "h3"},
						{title: "Header 4", format: "h4"},
						{title: "Header 5", format: "h5"},
						{title: "Header 6", format: "h6"}
					]},
				{title: "Inline", items: [
						{title: "Bold", icon: "bold", format: "bold"},
						{title: "Italic", icon: "italic", format: "italic"},
						{title: "Underline", icon: "underline", format: "underline"},
						{title: "Code", icon: "code", format: "code"}
					]}
			]
		});
	});


	$(document).on('click', '.remove-option', function () {
		$(this).parents('.option-row').remove();
	});
});
