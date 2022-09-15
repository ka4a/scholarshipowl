$(function() {
  var collegeInstructions = "Name of your college";
  var slect2dropDown = function () {
    $("#collegePicker").select2({
        tags: true,
        placeholder: "--- Select ---",
        language: {
          inputTooShort: function () {
            return collegeInstructions;
          }
        },
        maximumSelectionLength: 10,
        ajax: {
          url: function(params) {
            return '/rest/v1/autocomplete/college/' + params.term.replace(/[\/]/gi, '');
          },
          dataType: 'json',
          delay: 250,
          global: false,
          processResults: function (response) {
            return {
              results: response.data
            };
          },
          cache: true
        },
        //    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1
        //    templateResult: formatRepo, // omitted for brevity, see the source of this page
        //    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
      })
      .on("select2:open", function () {
        $("ul[aria-multiselectable='true'] li.select2-results__option").filter(function () {
          return $(this).text() === collegeInstructions;
        }).css({"padding": "17px 6px", "font-size": "13px"});
        $("input.select2-search__field").attr("placeholder", "Start typing to select your intended colleges").css("font-size", "14px");
      });
  };

  slect2dropDown();

  var $oneCollegeOption = $("#singleCollege").html() ? $("#singleCollege").html() : $("<option value='x'>Start typing to select your college</option>"),
    $multipleCollegesOptions = null;

  var collegeInputChange = function () {
    var $collegePicker = $('#collegePicker'),
      $enrollmentDate = $("#enrollmentDate label");

    if ($("#enrolledNo").prop("checked")) {

      $collegePicker
        .select2("destroy")
        .prop("multiple", true)
        .find('option[value="x"]').remove();

      if ($multipleCollegesOptions) {
        $collegePicker.html($multipleCollegesOptions);
      }

      slect2dropDown();

      if (!$collegePicker.find('option').length) {
        $("input.select2-search__field[tabindex='-1']")
          .attr("placeholder", "Start typing to select your intended colleges")
          .css({"width": "100%", "padding": "2px 0", "font-size": "12px", "color": "#333"});
      }

      collegeInstructions = "Select three or more intended colleges";
      $enrollmentDate.html("Estimated Enrollment Date");

    } else if ($("#enrolledYes").prop("checked")) {

      $multipleCollegesOptions = $collegePicker.html();

      $collegePicker
        .select2('destroy')
        .removeAttr('multiple')
        .html($oneCollegeOption);

      slect2dropDown();

      collegeInstructions = "Name of your college";
      $enrollmentDate.html("Enrollment Date");

    }
  };

  $("#enrolledYes, #enrolledNo").on("change", collegeInputChange);
  $(collegeInputChange);

  $("#highSchoolPicker").select2({
    tags: true,
    placeholder: "--- Select ---",
    language: {
      inputTooShort: function () {
        return "Name of your high school";
      }
    },
    ajax: {
      url: function(params) {
        return '/rest/v1/autocomplete/highschool/' + params.term.replace(/[\/]/gi, '')
      },
      dataType: 'json',
      global: false,
      delay: 250,
      processResults: function (response) {
        return {
          results: response.data
        };
      },
      cache: true
    },
    //    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 1
    //    templateResult: formatRepo, // omitted for brevity, see the source of this page
    //    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
  });

   $("#study_country").select2();

  $("#militaryPicker").select2({
    tags: true,
    placeholder: "--- Select ---",
    language: {
      inputTooShort: function () {
        return "Military Affiliation";
      }
    },
    ajax: {
      url: "/militaryAffiliationAutocomplete",
      dataType: 'json',
      global: false,
      delay: 250,
      data: function (params) {
        return {
          g: params.term, // search term
          page: params.page
        };
      },
      processResults: function (data, page) {
        var results = [];
        for (var i in data.data) {
          results.push({id: i, text: data.data[i]});
        }
        return {
          results: results
        };
      },
      cache: true
    },
    minimumInputLength: 1
  });

  var studentOrParent = $('.student_or_parent');
  studentOrParent.selectpicker().val(studentOrParent.data('type')).selectpicker('refresh');
});
