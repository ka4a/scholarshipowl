
var ScholarshipsRequirementsForm = Element.extend({
  _init: function (element) {
    this._super(element);
    var that = this,
      $this = this.$this,
      $requirementTexts = $this.find('.container-requirement-texts'),
      $requirementFiles = $this.find('.container-requirement-files'),
      $requirementImages = $this.find('.container-requirement-images'),
      $requirementInputs = $this.find('.container-requirement-inputs'),
      $requirementSurveys = $this.find('.container-requirement-surveys'),
      $requirementSpecialEligibility = $this.find('.container-requirement-special-eligibility'),
      requirementTextTemplateHtml = $this.find('.template-requirement-text').html(),
      requirementFileTemplateHtml = $this.find('.template-requirement-file').html(),
      requirementImageTemplateHtml = $this.find('.template-requirement-image').html();
      requirementInputTemplateHtml = $this.find('.template-requirement-input').html();
      requirementSurveyTemplateHtml = $this.find('.template-requirement-survey').html();
      requirementSpecialEligibilityTemplateHtml = $this.find('.template-requirement-special-eligibility').html();

    $requirementTexts.find('.requirement-text')
      .each(function() { that._initRequirementText($(this)); });
	$requirementSpecialEligibility.find('.requirement-special-eligibility')
      .each(function() { that._initRequirementSpecialEligibility($(this)); });
    $requirementFiles.find('.requirement-file')
      .each(function() { that._initRequirementFile($(this)); });
    $requirementImages.find('.requirement-image')
      .each(function() { that._initRequirementImage($(this)); });
    $requirementImages.find('.requirement-input')
      .each(function() { that._initRequirementInput($(this)); });
    $requirementImages.find('.requirement-survey')
      .each(function() { that._initRequirementSurvey($(this)); });

    $this.find('.btn-add-requirement-text').click(function() {
      var maxIndex = that._getMaxIndex($requirementTexts.find('.requirement-text')),
        $requirementText = that._initRequirementText(
          $(requirementTextTemplateHtml.replace(/%index%/g, maxIndex + 1))
        );

      $requirementTexts.append($requirementText);
    });

    $this.find('.btn-add-requirement-file').click(function() {
      // var $requirementFiles = $this.find(that._selectors.requirementFiles),
      var maxIndex = that._getMaxIndex($requirementFiles.find('.requirement-file')),
        $requirementFile = that._initRequirementFile(
          $(requirementFileTemplateHtml.replace(/%index%/g, maxIndex + 1))
        );

      $requirementFiles.append($requirementFile);
    });

    $this.find('.btn-add-requirement-image').click(function() {
      // var $requirementFiles = $this.find(that._selectors.requirementFiles),
      var maxIndex = that._getMaxIndex($requirementImages.find('.requirement-image')),
        $requirementImage = that._initRequirementImage(
          $(requirementImageTemplateHtml.replace(/%index%/g, maxIndex + 1))
        );

      $requirementImages.append($requirementImage);
    });

    $this.find('.btn-add-requirement-input').click(function(e) {
        e.preventDefault();
      var maxIndex = that._getMaxIndex($requirementInputs.find('.requirement-input')),
        $requirementInput = that._initRequirementInput(
          $(requirementInputTemplateHtml.replace(/%index%/g, maxIndex + 1))
        );

      $requirementInputs.append($requirementInput);
    });

    $this.find('.btn-add-requirement-survey').click(function(e) {
        e.preventDefault();

        var maxIndex = that._getMaxIndex($requirementSurveys.find('.requirement-survey'));
		$requirementSurvey = that._initRequirementSurvey(
			$(requirementSurveyTemplateHtml.replace(/%index%/g, maxIndex + 1))
		);
		$requirementSurveys.append($requirementSurvey);
    });

	  $('#tab-requirements').on('click', '.btn-option-delete', function() {
		  $(this).parents('.options-container').remove();
	  });

	  $('#tab-requirements').on('click', '.btn-new-question', function() {
		    $surveyIndex = $(this).parents('.requirement-survey').attr('data-index');
		    var numberOfContainers = $(this).parents('.survey-box').find('.survey-containter').length;
		    if(numberOfContainers != 0) {
				var newId = numberOfContainers;
			}

		    $(this).before(
			"<div class=\"col-xs-12 survey-containter\">\n" +
			"                                <input class=\"survey_id\" name=\"\" type=\"hidden\" value=\""+numberOfContainers+"\">\n" +
			"                                <table class=\"table\">\n" +
			"                                    <tbody>" +
				"                                    <tr>\n" +
				"                                        <td style='text-align: left;' colspan=\"3\">\n" +
				"                                            <a class='btn btn-danger btn-question-delete'>Delete question</a>\n" +
				"                                        </td>\n" +
				"                                    </tr>" +
				"<tr>\n" +
			"                                        <td>Question type</td>\n" +
			"                                        <td>\n" +
			"                                            <select class=\"form-control\" name=\"requirement_survey["+$surveyIndex+"][survey]["+numberOfContainers+"][type]\">" +
			"											<option value=\"checkbox\">Multiple choice</option><option value=\"radio\" selected=\"selected\">Single answer</option></select>\n" +
			"                                            <div class=\"alert alert-warning\" role=\"alert\">\n" +
				"                                            <p>-Multiple choice: user can select multiple answers</p>\n" +
				"                                            <p>-Single answer: user can select only one answer</p>\n" +
		"                                                                                            </div>\n" +
			"                                        </td>\n" +
			"                                    </tr>\n" +
			"                                    <tr>\n" +
			"                                        <td>Short description/instruction</td>\n" +
			"                                        <td>\n" +
			"                                            <textarea placeholder='On scale 1-5 grade year interest in getting this scholarship.' class=\"form-control\" name=\"requirement_survey["+$surveyIndex+"][survey]["+numberOfContainers+"][description]\" cols=\"50\" rows=\"10\"></textarea>\n" +
			"                                        </td>\n" +
			"                                    </tr>\n" +
			"                                    <tr>\n" +
			"                                        <td>Question</td>\n" +
			"                                        <td>\n" +
			"                                            <textarea placeholder='Enter question text' class=\"form-control\" name=\"requirement_survey["+$surveyIndex+"][survey]["+numberOfContainers+"][question]\" cols=\"50\" rows=\"10\"></textarea>\n" +
			"                                        </td>\n" +
			"                                    </tr>\n" +
			"                                    <tr>\n" +
			"                                        <td>Suggested answers/options::</td>\n" +
			"                                        <td>\n" +
			"                                            <table class=\"options table\">\n" +
															" <tbody>" +
															" </tbody></table>\n" +
			"                                            <a class=\"btn btn-success btn-option-add\">Add option</a>\n" +
			"\n" +
			"                                        </td>\n" +
			"                                    </tr>\n" +
			"\n" +
			"                                </tbody></table>\n" +
			"\n" +
			"                            </div>");
				  });

	  $('#tab-requirements').on('click', '.btn-option-add', function() {
		  var surveyContainerNum = $(this).parents('.survey-containter').find(".survey_id").val();
		  var surveyIndex = $(this).parents('.requirement-survey').attr('data-index');
		  var lastOptionName = $(this).parent('td').find('input:last').attr('name');
		  var optionId = lastOptionName ? (+lastOptionName.match(/(\d)+]$/)[1]) + 1 : 1;
		  $($(this).parents('td')[0]).find('.options>tbody').append(
				"  <tr class=\"options-container\">\n" +
			  "\t  <td> <input placeholder=\"Enter a suggested answer\" class=\"form-control\" name=\"requirement_survey["+surveyIndex+"][survey]["+surveyContainerNum+"][options]["+optionId+"]\" type=\"text\" value=\"\">\n" +
			  "\t\t  </td>\n" +
			  "\t\t  <td>\n" +
			  "\t\t  <a class=\"btn btn-danger btn-option-delete\">Remove option</a>\n" +
			  "\t  </td>\n" +
			  "\t  </tr>"
		  );
	  });

	  $('#tab-requirements').on('click', '.btn-question-delete', function() {
		  $(this).parents('.survey-containter').remove();
	  });

	  $('#tab-requirements').on('click', '.btn-requirement-survey-delete', function() {
		  $(this).parents('.requirement-survey').remove();
	  });


	  $this.find('.btn-add-requirement-spec-eligibility').click(function(e) {
		  e.preventDefault();
		  var maxIndex = that._getMaxIndex($requirementSpecialEligibility.find('.requirement-special-eligibility')),
			  $spercEli = that._initRequirementSpecialEligibility(
				  $(requirementSpecialEligibilityTemplateHtml.replace(/%index%/g, maxIndex + 1))
			  );

		  $requirementSpecialEligibility.append($spercEli);
	  });
  },

  _initRequirementText: function($requirementText) {
    var $fileConfiguration = $requirementText.find('.file-configuration'),
      $attachmentConfiguration = $requirementText.find('.attachment-config'),
      $sendTypeSelect = $requirementText.find('.send-type-select'),
      $allowFileSelect = $requirementText.find('.allow-file-select'),
      $fileExtensionInput = $requirementText.find('input.file-extension'),
      $maxFileAllowInput = $requirementText.find('input.max-file-size'),

      toggleFileConfiguration = function() {
        if ($allowFileSelect.val() === '1') {
          $fileConfiguration.show();
        } else {
          $fileConfiguration.hide();
          $fileExtensionInput.val('');
          $maxFileAllowInput.val('');
        }
      },

      toggleSendTypeConfigurations = function() {
        if ($sendTypeSelect.val() === 'body') {
          $attachmentConfiguration.hide();
          $allowFileSelect.val(0).prop('disabled', 'disabled').change();
          $fileExtensionInput.val('');
          $maxFileAllowInput.val('');
        } else {
          $attachmentConfiguration.show();
          $allowFileSelect.prop('disabled', false);
        }
      };

    $requirementText.find('.btn-requirement-delete').click(function() {
      $requirementText.remove();
    });

    toggleFileConfiguration();
    toggleSendTypeConfigurations();
    $allowFileSelect.change(toggleFileConfiguration);
    $sendTypeSelect.change(toggleSendTypeConfigurations);

    $requirementText.find('[data-toggle="tooltip"]').tooltip();
    return $requirementText;
  },

  _initRequirementFile: function($requirementFile) {
    $requirementFile.find('.btn-requirement-delete').click(function() {
      $requirementFile.remove();
    });

    $requirementFile.find('[data-toggle="tooltip"]').tooltip();
    return $requirementFile;
  },

  _initRequirementImage: function($requirementImage) {
    $requirementImage.find('.btn-requirement-delete').click(function() {
      $requirementImage.remove();
    });

    $requirementImage.find('[data-toggle="tooltip"]').tooltip();
    return $requirementImage;
  },

  _initRequirementInput: function($requirementInput) {
    $requirementInput.find('.btn-requirement-delete').click(function() {
      $requirementInput.remove();
    });

    $requirementInput.find('[data-toggle="tooltip"]').tooltip();
    return $requirementInput;
  },

  _initRequirementSurvey: function($requirementSurvey) {
    $requirementSurvey.find('.btn-requirement-delete').click(function() {
		$requirementSurvey.remove();
    });

	  $requirementSurvey.find('[data-toggle="tooltip"]').tooltip();
    return $requirementSurvey;
  },

	_initRequirementSpecialEligibility: function($requirementSpecialEligibility) {
    $requirementSpecialEligibility.find('.btn-requirement-delete').click(function() {
		$requirementSpecialEligibility.remove();
    });

		$requirementSpecialEligibility.find('[data-toggle="tooltip"]').tooltip();
    return $requirementSpecialEligibility;
  },

  _getMaxIndex: function (collection) {
    var maxIndex = 0;

    // this.$this.find(this._selectors.requirementFiles).find('.requirement-file').each(function() {
    collection.each(function() {
      var index = parseInt($(this).attr('data-index'), 0);

      if (index > maxIndex) {
        maxIndex = index;
      }
    });

    return maxIndex;
  }
});

/*
 * OnlineApplicationFetchFormButton JS Class
 * By Marko Prelic
 */
var OnlineApplicationFetchFormButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		element.bind("click", function(e) {
			e.preventDefault();

			var $url = $("input[name=apply_url]").val();
			var $scholarshipId = $("input[name=scholarship_id]").val();

			if (!$url) {
				alert("Scholarship apply url is empty !");
				return false;
			}

			var $ajax = new Ajax("/admin/scholarships/fetch-form?url=" + $url, "get", "json", { url: $url, scholarship_id: $scholarshipId });

			$ajax.onBeforeSend = function(request) {
				caller.attr("disabled", "disabled");
			};

			$ajax.onSuccess = function(response) {
				if (response.data.forms) {
					$("#OnlineApplicationFormContainer").html(response.data.forms);

					$("#OnlineApplicationFormContainer input[type != hidden]").each(function() {
						caller.createButton($(this), "input");
					});

					$("#OnlineApplicationFormContainer select").each(function() {
						caller.createButton($(this), "select");
					});

					$("#OnlineApplicationFormContainer button").each(function() {
						caller.createButton($(this), "button");
					});

					$("#OnlineApplicationFormContainer textarea").each(function() {
						caller.createButton($(this), "textarea");
					});

					$("#OnlineApplicationFormContainer option").each(function() {
						var $optionText = $(this).text();
						var $optionValue = $(this).val();

						$optionText += " (" + $optionValue + ")";
						$(this).text($optionText);
					});

					$(".OnlineApplicationFormFieldButton").each(function() {
						new OnlineApplicationFormFieldButton($(this));
					});

					$.each(response.data.fields, function(key, value) {
						$(".OnlineApplicationFormFieldButton[data-name='" + key + "']").removeClass("btn-warning");
						$(".OnlineApplicationFormFieldButton[data-name='" + key + "']").addClass("btn-primary");
					});
				}
			};

			$ajax.onComplete = function() {
				caller.removeAttr("disabled");
			};

			$ajax.sendRequest();
		});
	},
	createButton: function(element, type) {
		var $name = element.attr("name");
		var $class = "btn btn-warning OnlineApplicationFormFieldButton";
		var $icon = "<i class='fa fa-check-square-o'></i>";
		var $button = $("<a href='#' data-name='" + $name + "' class='" + $class + "'>" + $icon + "</a>");

		if (type == "input") {
			element.after($button);

			var $inputType = element.attr("type");
			if ($inputType && ($inputType.toLowerCase() == "submit" || $inputType.toLowerCase() == "button" || $inputType.toLowerCase() == "file")) {
				element.attr("disabled", "disabled");
			}
      $button.data('input-type', $inputType);
		}
		else if (type == "select" || type == "textarea") {
			element.after($button);
		}
		else if (type == "button") {
			element.attr("disabled", "disabled");
			element.after($button);
		}
	}
});


/*
 * OnlineApplicationFormFieldButton JS Class
 * By Marko Prelic
 */
var OnlineApplicationFormFieldButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		element.bind("click", function(e) {
			e.preventDefault();

			var $data = {
				scholarship_id: $("input[name=scholarship_id]").val(),
				form_field: caller.attr("data-name")
			};

			$.get("/admin/scholarships/fetch-field", $data)
				.done(function(response) {
					var $prev = caller.prev().clone();
					var $dataName = caller.attr("data-name");
          var $response = $(response);

					$("input[name=SaveFieldName]").val($dataName);

          new OnlineApplicationFieldValueSelect($response);

					$("#OnlineApplicationFieldEditor #MappingFields").html($response);

					var $currentValue = $("#OnlineApplicationFieldEditor input[name=MappingFieldDefaultValue]").val();
					if (!$currentValue && !$prev.is("select") && $prev.val()) {
						$("#OnlineApplicationFieldEditor input[name=MappingFieldDefaultValue]").val($prev.val().trim());
					}

					caller.initMappingFieldsSelect();
					caller.initAllMappings();

					$("#MappingFieldsSelect").change(function() {
						caller.initMappingFieldsSelect();
					});

					$("#OnlineApplicationFieldEditor").modal();
				});
		});
	},
	initMappingFieldsSelect: function() {
		var $field = $("#MappingFieldsSelect").find(":selected").attr("value");
		var $isMulti = $("#MappingFieldsSelect").find(":selected").attr("data-multi");

		if ($isMulti) {
			//$("#AllMappings input[type=hidden][data-field != '" + $field + "']").remove();

			$("#MappingSystemField").html("");
			$("#MappingSystemField").attr("disabled", "disabled");
			$("input[type=hidden][name=StaticSystemField][data-field='" + $field + "']").each(function(k, v) {
				$("#MappingSystemField").append("<option value='" + $(v).attr("data-key") + "'>" + $(v).attr("data-value") + " (" + $(v).attr("data-key") + ")</option>");
			});

			$("#MappingFormField").html("");
			$("#SelectedFieldElement option").each(function(k, v) {
				$("#MappingFormField").append("<option value='" + $(v).attr("value") + "'>" + $(v).text() + "</option>");
			});
			$("#SelectedFieldElement input[type=radio]").each(function(k, v) {
				$("#MappingFormField").append("<option value='" + $(v).attr("value") + "'>" + $(v).attr("value") + "</option>");
			});

			$("#MappingEditor").show();
		}
		else {
			$("#MappingEditor").hide();
		}
	},
	initAllMappings: function() {
		$("#MappingFormField").change(function() {
			var $formName = $("#MappingFieldsSelect").val();
			var $formValue = $("#MappingFormField").find(":selected").attr("value");

			if ($formName) {
				$("#MappingSystemField option").removeAttr("selected");

				$("#AllMappings input[type=hidden][data-field='" + $formName + "'][data-value='" + $formValue + "']").each(function(k, v) {
					$("#MappingSystemField option[value='" + $(v).val() + "']").attr("selected", true);
				});

				$("#MappingSystemField").removeAttr("disabled");
			}
			else {
				$("#MappingSystemField").attr("disabled", "disabled");
			}
		});
		$("#MappingSystemField").change(function() {
			var $formName = $("#MappingFieldsSelect").val();
			var $formValue = $("#MappingFormField").find(":selected").attr("value");

			if ($formName) {
				$("#AllMappings input[type=hidden][data-field='" + $formName + "'][data-value='" + $formValue + "']").remove();

				$(this).find(":selected").each(function(k, v) {
					var $hidden = "<input type='hidden' name='mapping[]' value='" + $(v).val() + "' data-field='" + $formName + "' data-value='" + $formValue + "' />";
					$("#AllMappings").append($hidden);
				});
			}
		});
	}
});


/*
 * OnlineApplicationSaveFieldButton JS Class
 * By Marko Prelic
 */
var OnlineApplicationSaveFieldButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		element.bind("click", function(e) {
			e.preventDefault();

			var $selectedField = $('[name=MappingFieldsSelect]').val(),
        $defaultValue = $('[name=MappingFieldDefaultValue]').val();

      if (typeof $defaultValue === 'undefined') {
        alert('Please select value before save');
        return;
      }

      $mapping = [];
			$("#AllMappings input[type=hidden][data-field='" + $selectedField + "']").each(function(k, v) {
				var $formValue = $(v).attr("data-value");
				var $systemValue = $(v).val();

				$mapping.push($formValue + "###" + $systemValue);
			});

			var $data = {
				scholarship_id: $("input[name=scholarship_id]").val(),
				form_field: $("input[name=SaveFieldName]").val(),
				system_field: $selectedField,
				value: $defaultValue,
				mapping: $mapping
			};

			var $ajax = new Ajax("/admin/scholarships/post-save-field", "post", "json", $data);

			$ajax.onBeforeSend = function(request) {
				caller.attr("disabled", "disabled");
			};

			$ajax.onSuccess = function(response) {
				if (response.status == "ok") {
					var $formField = $("input[name=SaveFieldName]").val();
					var $systemField = $selectedField;

					$(".OnlineApplicationFormFieldButton[data-name='" + $formField + "']").removeClass("btn-warning");
					$(".OnlineApplicationFormFieldButton[data-name='" + $formField + "']").addClass("btn-primary");
					$("#OnlineApplicationFieldEditor").modal("hide");

          var $tr = $(
            "<tr data-form-field='" + $formField + "'><td>" + $formField + "</td><td>" + $systemField + "</td><td>" + $defaultValue + "</td>" +
              '<td><a class="btn btn-danger pull-right OnlineApplicationFormFieldButton" data-name="' + $formField + '"><i class="fa fa-edit"></i> Edit</a></td>' +
            "</tr>"
          );

          new OnlineApplicationFormFieldButton($tr.find('.OnlineApplicationFormFieldButton'));
          $("#OnlineDataTable tbody").append($tr);
				}
				else if (response.status == "error") {
					alert(response.message);
				}
			};

			$ajax.onComplete = function() {
				caller.removeAttr("disabled");
			};

			$ajax.sendRequest();
		});
	}
});

var OnlineApplicationFieldValueSelect = Element.extend({
  _init: function(element) {
    this._super(element);
    var $mainContainer = this.$this.find('.FieldInputContainer'),
      $fieldsSelect = this.$this.find('[name=MappingFieldsSelect]'),
      $requirementTextContainer = $mainContainer.find('.RequirementTextContainer'),
      $requirementFileContainer = $mainContainer.find('.RequirementFileContainer'),
      $requirementImageContainer = $mainContainer.find('.RequirementImageContainer'),
      $requirementInputContainer = $mainContainer.find('.RequirementInputContainer'),
      $fieldDefault = $mainContainer.find('.FieldDefault'),
      $fieldText = $mainContainer.find('.FieldText'),

      funcGetContainer = function(systemField) {
        switch (systemField) {
          case 'upload_text_field':
            return $requirementTextContainer;
          case 'upload_file_field':
            return $requirementFileContainer;
          case 'upload_image_field':
            return $requirementImageContainer;
          case 'requirement_input_field':
            return $requirementInputContainer;
          case 'requirement_text_field':
            return $fieldText;
          default:
            break;
        }

        return $fieldDefault;
      },

      showFieldValueContainer = function() {
        var $container;
        $mainContainer.find('.InputContainer').detach();
        if ($container = funcGetContainer($fieldsSelect.val())) {
          $container.appendTo($mainContainer);
        }
      };

    $fieldsSelect.change(showFieldValueContainer);
    showFieldValueContainer();
  }
});

/*
 * OnlineApplicationDeleteFieldButton JS Class
 * By Marko Prelic
 */
var OnlineApplicationDeleteFieldButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		element.bind("click", function(e) {
			e.preventDefault();

			var $scholarshipId = $("input[name=scholarship_id]").val();
			var $formField = $("input[name=SaveFieldName]").val();

			if (!$formField) {
				return false;
			}

			var $data = {
				scholarship_id: $scholarshipId,
				form_field: $formField
			};


			var $ajax = new Ajax("/admin/scholarships/post-delete-field", "post", "json", $data);

			$ajax.onBeforeSend = function(request) {
				caller.attr("disabled", "disabled");
			};

			$ajax.onSuccess = function(response) {
				if (response.status == "ok") {
					var $formField = $("input[name=SaveFieldName]").val();

					$(".OnlineApplicationFormFieldButton[data-name='" + $formField + "']").removeClass("btn-primary");
					$(".OnlineApplicationFormFieldButton[data-name='" + $formField + "']").addClass("btn-warning");
					$("#OnlineApplicationFieldEditor").modal("hide");

					$("#OnlineDataTable tbody tr[data-form-field='" + $formField + "']").remove();
				}
				else if (response.status == "error") {
					alert(response.message);
				}
			};

			$ajax.onComplete = function() {
				caller.removeAttr("disabled");
			};

			$ajax.sendRequest();
		});
	}
});




/*
 * SaveScholarshipForm JS Class
 * By Marko Prelic
 */
var SaveScholarshipForm = Element.extend({
	_init: function(element) {
		this._super(element);

		var caller = this;
		var select = new FormElement("select[name=application_type]");

		select.bind("change", function() {
			caller.changeType(select.getValue());
		});

		this.changeType(select.getValue());
	},

	changeType: function(type) {
		if(type == "online") {
			$("#ApplyUrl").show();
			$("#ApplicationTypeOnlinePanel").show();
			$("#ApplicationTypeEmailPanel").hide();
		}
		else if(type == "email") {
			$("#ApplyUrl").show();
			$("#ApplicationTypeOnlinePanel").hide();
			$("#ApplicationTypeEmailPanel").show();
		}
		else {
			$("#ApplyUrl").hide();
			$("#ApplicationTypeOnlinePanel").hide();
			$("#ApplicationTypeEmailPanel").hide();
		}
	},
});



/*
 * DeleteScholarshipButton JS Class
 * By Marko Prelic
 */
var DeleteScholarshipButton = Element.extend({
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
	},
});


/*
 * FetchScholarshipFieldsButton JS Class
 * By Marko Prelic
 */
var FetchScholarshipFieldsButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		element.bind("click", function(e) {
			e.preventDefault();
			var $url = $("input[name=apply_url]").val();

			if(!$url) {
				alert("Scholarship url is empty");
			}
			else {
				var $ajax = new Ajax("/admin/scholarships/fetch-url", "get", "json", { url: $url });

				$ajax.onBeforeSend = function() {
					caller.attr("disabled", "disabled");
					$("#ScholarshipFieldsTable tbody").html("");
				};

				$ajax.onSuccess = function(response) {
					if(response.status == "ok") {
						var $data = response.data;

						var $formMethod = new FormElement("#form_method");
						$formMethod.setValue($data.method);

						var $formAction = new FormElement("#form_action");
						$formAction.setValue($data.action);

						var $fields = new Element("#Fields");


						$.each($data.fields, function() {
							var $field = this;

							var $tableBody = new Element("#ScholarshipFieldsTable tbody");
							var $tableRow = "<tr>";

							$tableRow += "<input type='hidden' value='" + $field.name + "' name='field_name[]' />"
							$tableRow += "<input type='hidden' value='" + $field.type + "' name='field_type[]' />"
							$tableRow += "<input type='hidden' value='" + $field.value_serialized + "' name='field_value_serialized[]' />"

							$tableRow += "<td>" + $field.name + "</td>";
							$tableRow += "<td>" + $field.type + "</td>";
							$tableRow += "<td>" + "<select name='field_id[]' class='populate placeholder select2'>" + $fields.getHtml() + "</select></td>";

							if($field.type == "select") {
								$tableRow += "<td>" + "<select name='field_value[]' class='populate placeholder select2'>";
								$.each($field.value, function(key, value) {
									$tableRow += "<option value='" + key + "'>" + value + "</option>";
								});
								$tableRow += "</select></td>";
							}
							else {
								if($field.value[0]) {
									$singleValue = $field.value[0];
								}
								else {
									$singleValue = "";
								}

								$tableRow += "<td><input name='field_value[]' class='form-control' disabled='disabled' value='" + $singleValue + "' /></td>";
							}

							$tableRow += "<td>" + "<a href='#' class='btn btn-danger DeleteScholarshipFieldButton'>Delete</a>" + "</td>";
							$tableRow += "</tr>";

							$tableBody.appendHtml($tableRow);
						});


						$("#ScholarshipFieldsTable tbody select").addClass("select2");
						$("#ScholarshipFieldsTable tbody .select2").select2();

						$(".DeleteScholarshipFieldButton").each(function() {
							new DeleteScholarshipFieldButton($(this));
						});
					}
					else if(response.status == "error") {
						alert(response.message);
					}
				};

				$ajax.onComplete = function() {
					caller.removeAttr("disabled");
				}

				$ajax.sendRequest();
			}
		});
	},
});



/*
 * DeleteScholarshipFieldButton JS Class
 * By Marko Prelic
 */
var DeleteScholarshipFieldButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();
			element.parent().parent().remove();
		});
	},
});


/*
 * SaveScholarshipInformation JS Class
 * By Marko Prelic
 */
var SaveScholarshipInformation = AjaxButton.extend({
	onBeforeSend: function(request) {
		$(this.getSelector()).attr("disabled", "disabled");

		$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
		$("#msgNotification").text("");
		$("#msgNotification").hide();

		$(".help-block").remove();
		$(".has-error").removeClass("has-error");
	},

	onSuccess: function(response) {
		if(response.status == "ok") {
			if(response.message) {
				$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
				$("#msgNotification").addClass("bg-common bg-success");
				$("#msgNotification").text(response.message);
				$("#msgNotification").show();
			}

			if(response.data > 1) {
				$("input[name=scholarship_id]").val(response.data);
			}
		}
		else if(response.status == "error") {
			if(response.message) {
				$("#msgNotification").removeClass("bg-common bg-danger bg-success bg-error bg-warning");
				$("#msgNotification").addClass("bg-common bg-danger");
				$("#msgNotification").text(response.message);
				$("#msgNotification").show();
			}

			$.each(response.data, function(k,v) {
				var $errorElement = "<small class='help-block col-sm-offset-3 col-sm-9'>" + v + "</small>";

				$("[name=" + k + "]").parent().parent().addClass("has-error");
				$("[name=" + k + "]").parent().parent().append($errorElement);
			});
		}
		else if(response.status == "redirect") {
			window.location = response.data;
		}
	},

	onComplete: function() {
		$(this.getSelector()).removeAttr("disabled");
		$("#msgNotification").delay(6000).fadeOut(500);
		$("html, body").animate({ scrollTop: 0 }, "slow");
	},
});



/*
 * AddEssayButton JS Class
 * By Marko Prelic
 */
var AddEssayButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var $time = new Date().getTime();
			var $id = "essay_" + $time;
			var $deleteButtonId = "essay_delete_" + $time;

			var $body = new Element("#ScholarshipEssaysTable tbody");
			var $html = "<tr id='" + $id + "'>";


			$html += "<td>";
			$html += "<input type='hidden' name='essay_id[]' value='' />";
			$html += "Title <br /><input type='text' class='form-control col-sm-3' name='essay_title[]' value='' />";
			$html += "Description <br /><textarea class='form-control' name='essay_description[]'></textarea>";
			$html += "</td>";

			$html += "<td>";
			$html += "Min. Words<br /> <input type='text' class='form-control col-sm-3' name='essay_min_words[]' value='' />";
			$html += "Max. Words<br /> <input type='text' class='form-control col-sm-3' name='essay_max_words[]' value='' />";
			$html += "Min. Characters<br /> <input type='text' class='form-control col-sm-3' name='essay_min_characters[]' value='' />";
			$html += "Max. Characters<br /> <input type='text' class='form-control col-sm-3' name='essay_max_characters[]' value='' />";
			$html += "</td>";

			$html += "<td>";
			$html += "Send Type <br /><select name='essay_send_type[]' class='select2'>" + $("#EssaySendTypesOptions").html() + "</select><br />";
			$html += "Attachment Type <br /><select name='essay_attachment_type[]' class='select2'>" + $("#EssayAttachmentTypesOptions").html() + "</select><br />";
			$html += "Attachment Format <br /><input type='text' class='form-control col-sm-3' name='essay_attachment_format[]' value='' />";
			$html += "Field Name <br /><input type='text' class='form-control' name='essay_field_name[]' value='' />";
			$html += "</td>";

			$html += "<td><a href='#' class='btn btn-danger DeleteEssayButton' id='" + $deleteButtonId + "'>Delete</a></td>";

			$html += "</tr>";
			$body.appendHtml($html);


			$("#" + $id + " .select2").select2({ closeOnSelect: false });
			$("#" + $deleteButtonId).each(function() {
				new DeleteEssayButton($(this));
			});
		});
	},
});


/*
 * DeleteEssayButton JS Class
 * By Marko Prelic
 */
var DeleteEssayButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			if (confirm("Delete this essay ?")) {
				element.parent().parent().remove();
			}
		});
	},
});


/*
 * AddEligibilityButton JS Class
 * By Marko Prelic
 */
var AddEligibilityButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			var isSunrise = +element.attr('data-isSunrise');
			var $time = new Date().getTime();
			var $id = "eligibility_" + $time;
			var $deleteButtonId = "eligibility_delete_" + $time;

			var $body = new Element("#ScholarshipEligibilityTable tbody");
			var $html = "<tr id='" + $id + "'>";

			var renderTypeOptions = function(fieldId) {
				var allowedTypes = window.eligibilityTypeMap[fieldId];

				var optionList = '';
				allowedTypes.forEach(function(item) {
					var isMultiValueType = ['in', 'nin', 'between'].includes(item);
					optionList += '<option data-multiple='+isMultiValueType+' value="'+item+'">'+item+'</option>';
				});

				return optionList;
			};

			$html += "<td><select id='" + $id + "_eligibility_field' name='eligibility_field[]' class='select2'>" + $("#StaticDataFields").html() + "</select></td>";
			$html += "<td><select id='" + $id + "_eligibility_type' name='eligibility_type[]' class='select2'></select></td>";
			$html += "<td id='" + $id + "_eligibility_value_td'></td>";
			if (isSunrise) {
				var index = $("#ScholarshipEligibilityTable tbody").find('tr').length;
				$html += '<td>';
				$html += '<input name="eligibility_is_optional['+index+']" type="hidden" value="0">';
				$html += '<input name="eligibility_is_optional['+index+']" type="checkbox" value="1">';
				$html += '</td>'
			}
			$html += "<td><a href='#' class='btn btn-danger DeleteEligibilityButton' id='" + $deleteButtonId + "'>Delete</a></td>";

			$html += "</tr>";
			$body.appendHtml($html);


			$("#" + $id + " .select2").select2({ closeOnSelect: false });
			$("#" + $deleteButtonId).each(function() {
				new DeleteEligibilityButton($(this));
			});

			$("#" + $id + "_eligibility_field").change(function(ev) {
				var $valueTd = $("#" + $id + "_eligibility_value_td");
				var $typeSelect = $("#" + $id + "_eligibility_type");
				var $elementId = $id + "_eligibility_value";
				var $elementName = "eligibility_value[]";

				$typeSelect.html(renderTypeOptions(+ev.val));
				$typeSelect.select2('val', '');

				var $html = "<input id='" + $elementId + "' name='" + $elementName + "' type='text' class='form-control' />";
				$valueTd.html($html);
			});

			$("#" + $id + "_eligibility_type").change(function() {
				var $option = $(this).find("option:selected");
				var $multipleSelect = $($option).attr("data-multiple");
				var $valueTd = $("#" + $id + "_eligibility_value_td");
				var $elementId = $id + "_eligibility_value";
				var $selectId = $id + "_eligibility_value_select";
				var $elementName = "eligibility_value[]";
				var $multipleValues = $("#" + $id + "_eligibility_field").find("option:selected").attr("data-multiple");

				if($multipleValues) {
					var multiplAttr = $multipleSelect === 'true' ? 'multiple="multiple"' : '';
					var $html = "<input type='hidden' name='" + $elementName + "' id='" + $elementId + "'><select id='" + $selectId + "' name='value_select[]' class='select2' " + multiplAttr + ">" + $("#StaticData" + $multipleValues).html() + "</select>";

					$valueTd.html($html);
					$("#" + $id + "_eligibility_value_select").select2({ closeOnSelect: false }).on("change", function(e) {
						$("#" + $id + "_eligibility_value").val(e.val);
					});
				}

				if ($option.val() === 'boolean') {
					var $html = '<label style="padding-right:5px" for="'+$id+'_radio_yes">Yes</label><input style="margin-right:10px" type="radio" id="'+$id+'_radio_yes" name="'+$elementName +'" value="1" checked>';
					$html += '<label style="padding-right:5px" for="'+$id+'_radio_no">No</label><input type="radio" id="'+$id+'_radio_no" name="'+$elementName +'" value="0">';

					$valueTd.html($html);
				}
			});
		});
	},
});


/*
 * DeleteEligibilityButton JS Class
 * By Marko Prelic
 */
var DeleteEligibilityButton = Element.extend({
	_init: function(element) {
		this._super(element);

		element.bind("click", function(e) {
			e.preventDefault();

			if (confirm("Delete this eligibility ?")) {
				element.parent().parent().remove();
			}
		});
	},
});

/**
 * Remove logo form element
 */
var RemoveScholarshipLogoButton = Element.extend({
    _init: function(element) {
        this._super(element);

        this.bind('click', function(e) {
            e.preventDefault();

            var scholarshipId = $(this).closest('form').find('[name=scholarship_id]').val();

            if (scholarshipId) {
                window.location = '/admin/scholarships/delete-logo?scholarshipId=' + scholarshipId;
            }
        });
    }
});
