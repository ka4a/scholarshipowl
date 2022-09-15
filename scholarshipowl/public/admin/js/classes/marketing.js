var BannersEdit = Element.extend({
  _init: function($element) {
    this._super($element);
    var $select = $element.find('[name=type]');

    var getType = function() {
      return $select.find('option[value=' + $select.val() + ']').text();
    };

    var typeChanged = function(type) {
      console.log('type', type === 'Image');
      $element.find('.banner-text').toggle(type === 'Text');
      $element.find('.banner-image').toggle(type === 'Image');
    };

    typeChanged(getType());

    $select.change(function() {
      typeChanged(getType());
    })
  }
});

var DeleteAffiliateGoalMappingButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");

			if(confirm($message)) {
				window.location = $url;
			}
		});
	}
});

var DeleteCoregPluginButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");

			if(confirm($message)) {
				window.location = $url;
			}
		});
	}
});

var DeleteMailchimpListButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");

			if(confirm($message)) {
				window.location = $url;
			}
		});
	}
});

/*
 * DeleteRulesSetButton JS Class
 * By Ivan Krkotic
 */
var DeleteRulesSetButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var $url = $(this).attr("data-delete-url");
			var $message = $(this).attr("data-delete-message");

			if(confirm($message)) {
				window.location = $url;
			}
		});
	}
});

/*
 * AddRedirectRuleButton JS Class
 * By Ivan Krkotic
 */
var AddRedirectRuleButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $html = "";
			var $timestamp = Date.now();

			$html += "<tr id=\"" + $timestamp + "\">";
			$html += "<td><select name='redirect_rule_field_" + $timestamp + "'class='form-control'>" + $("#profile-fields").html() + "</select></td>";
			$html += "<td><select name='redirect_rule_operator_" + $timestamp + "' class='form-control'>" + $("#operators").html() + "</select></td>";
			$html += "<td><input type='text' name='redirect_rule_value_" + $timestamp + "' value='' class='form-control' /></td>";
			$html += "<td><input type='checkbox' name='redirect_rule_active_" + $timestamp + "' value='1' /></td>";
			$html += "<td><a href='#' class='btn btn-danger DeleteRedirectRuleButton' data-timestamp='" + $timestamp + "' data-mission-goal-id=''>Delete</a></td>";
			$html += "</tr>";

			$("#RedirectRulesTable tbody").append($html);
			$(".DeleteRedirectRuleButton[data-timestamp=" + $timestamp + "]").each(function() {
				new DeleteRedirectRuleButton($(this));
			});
		});
	}
});

var AddCoregRequirements = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $html = "";
			var $timestamp = Date.now();
			var ruleGroup = $timestamp+1;

			$html += "<tr class='new-requirements-rules' id=\"" + $timestamp + "\">";
			$html += "<td><select name='requirements_rule["+ruleGroup+"][" + $timestamp + "][field]'class='form-control requirements_rule_field'>" + $("#profile-fields").html() + "</select></td>";
			$html += "<td><select name='requirements_rule["+ruleGroup+"][" + $timestamp + "][operator]' class='form-control requirements_rule_operator'>" + $("#operators").html() + "</select></td>";
			$html += "<td><input type='text' name='requirements_rule["+ruleGroup+"][" + $timestamp + "][value]' value='' class='form-control requirements_rule_value' /></td>";
			$html += "<td><input type='checkbox' name='requirements_rule["+ruleGroup+"][" + $timestamp + "][active]' hidden='hidden' checked='checked' value='0' /> <input type='checkbox' name='requirements_rule["+ruleGroup+"][" + $timestamp + "][active]' value='1' /></td>";
			$html += "<td><input type='checkbox' name='requirements_rule["+ruleGroup+"][" + $timestamp + "][send]' hidden='hidden' checked='checked' value='0' /> <input type='checkbox' name='requirements_rule["+ruleGroup+"][" + $timestamp + "][send]' value='1' /></td>";
			$html += "<td><a href='#' class='btn btn-danger DeleteRedirectRuleButton' data-timestamp='" + $timestamp + "' data-mission-goal-id=''>Delete</a></td>";
			$html += "</tr>";

			$("#RedirectRulesTable tbody").append($html);
			$(".DeleteRedirectRuleButton[data-timestamp=" + $timestamp + "]").each(function() {
				new DeleteRedirectRuleButton($(this));
			});
		});
	}
});

/*
 * DeleteRedirectRuleButton JS Class
 * By Ivan Krkotic
 */
var DeleteRedirectRuleButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			e.preventDefault();

			var $redirectRuleId = $(caller).parent().parent().attr("data-redirect-rule-id");

			if ($redirectRuleId) {
				var $ajax = new Ajax("/admin/marketing/delete-redirect-rule/" + $redirectRuleId);
				$ajax.onSuccess = function(data) {
					if (data.status == "ok") {
						$(caller).parent().parent().remove();
					}
					else if (data.status == "error") {
						alert("Error deleting redirect rule");
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


var SaveCoregPlugin = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		this.bind("click", function(e) {
			var submit = true;
			e.preventDefault();
			$('.validation-error').remove();
            $('#RedirectRulesTable').find('input, select').each(function(e,input){


            	if($(input).hasClass('requirements_rule_operator') && $(input).val() == "SET"){
                    return false;
				}

				if($(input).val() == "SET"){
                    $(input).parents('tr').find('.rule-value').addClass('value-for-set');
				}


				if((($(input).val() == "" || $(input).val() == "--- Select ---")) && !$(input).hasClass('value-for-set')){
					$(input).parents('tr').after('<tr class="validation-error"><td colspan="6"><div class="alert alert-danger fade in">All field should be filled</div></td></tr>');
					submit = false;
					return false;
				}
            });
            if(submit){
                $(this).parents('form').submit();
            }
		});
	}
});

/*
 * DeleteRulesSetButton JS Class
 * By Ivan Krkotic
 */
var DeleteTransactionalEmailButton = Element.extend({
    _init: function(element) {
        this._super(element);

        element.bind("click", function(e) {
            e.preventDefault();

            var $url = $(this).attr("data-delete-url");
            var $message = $(this).attr("data-delete-message");

            if(confirm($message)) {
                window.location = $url;
            }
        });
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})