// validation eligibility home page form
$(function() {
  var USER_MAX_AGE = 16;

  function defineUserAge() {
    var birthDate = new Date(
      Number(document.querySelector("select[name='birthday_year']").value),
      Number(document.querySelector("select[name='birthday_month']").value) - 1,
      Number(document.querySelector("select[name='birthday_day']").value)
    );

    return Math.abs(new Date(Date.now() - birthDate.getTime()).getFullYear() - 1970);
  }

  function saveDataToLocalStorage() {
    /**
     * Relates to error https://sentry.io/organizations/scholarshipowl/issues/1197222465/?project=102054&query=is%3Aunresolved
     * for old OperaMini clients. In register1 step fall back to
     * default behavior with hardcoded data for eligible count and amount
     * if localStorage is not defined
     */
    if(!window.localStorage) return;

    var data = {
      age: defineUserAge(),
      school_level: Number(document.querySelector("select[name='school_level_id']").value),
      degree: Number(document.querySelector("select[name='degree_id']").value),
      gender: document.querySelector("select[name='gender']").value
    }

    window.localStorage.setItem("home-page", JSON.stringify(data));
  }

  var interactionItems = [
    {item: 'select.year', parent: '.bootstrap-select.year', errorText: 'Please enter correct birth year !', isValid: false},
    {item: 'select.month', parent: '.bootstrap-select.month', errorText: 'Please enter correct birth month !', isValid: false},
    {item: 'select.day', parent: '.bootstrap-select.day', errorText: 'Please enter correct birth day !', isValid: false},
    {item: 'select.Gender', parent: 'div.Gender', errorText: 'Please select your gender !', isValid: false},
    {item: 'select.school-level', parent: 'div.school-level', errorText: 'Please select current school level !', isValid: false},
    {item: 'select.degree', parent: 'div.degree', errorText: 'Please select field of study !', isValid: false},
  ]

  var isRequiredAge = true;
  var elemBirthDate = document.querySelector("#birth-date");

  var alertFill = "<div style='z-index:666' id='alertFillContainer' \
    class='alert- alert-dismissible- fade in' \
    data-alertid='errorNotification' role='alert++'> \
    <div id='alertFill' class='center-block'> \
    <button type='button' class='close-alert' data-dismiss='close-alert' aria-label='Close Alert'> \
    <span aria-hidden='true'>&times;</span> \
    </button> \
    You must <strong>fill all required<br> fields</strong> in order to apply. \
    </div></div>";

  var button = document.querySelector('.EligibilityButton');
  if(!button) throw Error('Element by .EligibilityButton selector not found!');

  button.disabled = false;

  button.addEventListener('click', function(e) {

    window.SOWLMixpanelTrack('HomepageCTA Click');

    if(!validator(interactionItems)) {
      e.preventDefault();
      return;
    }

    saveDataToLocalStorage()

    this.querySelector('.arrow').style.display = 'none';
    this.querySelector('.button-loader').style.display = 'block';
  });

  function validator(validationSet) {
    var isValid = true;

    for(var i = 0; i < validationSet.length; i += 1) {
      validationSet[i].isValid = selectValidator(validationSet[i].item);
      isValid = isValid && validationSet[i].isValid;

      if(!validationSet[i].isValid) addError(validationSet[i]);
    }

    if(defineUserAge() < USER_MAX_AGE) {
      isRequiredAge = false;
      isValid = false;

      elemBirthDate.classList.add('error');

      var errorMess = document.createElement('small');
      errorMess.className  = "error-message error-message-age";
      errorMess.innerHTML = "You must be 16 years old";

      elemBirthDate.appendChild(errorMess);
    }

    return isValid;
  }

  function addError(item) {
    if (item.parent) {
      item.parent.classList.add('error');
      item.parent.appendChild(item.errorText);
    }
  }

  function removeError(item) {
    if (item && item.parent && item.errorText && item.errorText.parentNode) {
      item.parent.classList.remove('error');
      item.errorText.parentNode.removeChild(item.errorText);
    }
  }

  function selectValidator(selectElement) {
    return (selectElement && selectElement.selectedIndex > 1);
  }

  function handleChange(e) {
    var index = parseInt(this.getAttribute('data-index'));

    if(defineUserAge() >= USER_MAX_AGE && !isRequiredAge) {
      isRequiredAge = true;

      elemBirthDate.classList.remove("error");

      var errorText = elemBirthDate.querySelector(".error-message-age");
      elemBirthDate.removeChild(errorText);
    }

    if(!interactionItems[index].isValid) {
      interactionItems[index].isValid = true;
      removeError(interactionItems[index]);
    }
  }

  for (var i = 0; i < interactionItems.length; i += 1) {
    var item = document.querySelector(interactionItems[i].item);
    if(item) {
      item.setAttribute('data-index', i);
      interactionItems[i].item = item
    } else {
      throw Error('Elememnt with ' + interactionItems[i].item + ' selector. Not defined!')
    }

    var parent = document.querySelector(interactionItems[i].parent);
    if(parent) {
      interactionItems[i].parent = parent
    } else {
      throw Error('Elememnt with ' + interactionItems[i].parent + ' selector. Not defined!');
    }

    var errorMess = document.createElement('small');
    errorMess.className  = 'error-message';
    errorMess.innerHTML = interactionItems[i].errorText;
    interactionItems[i].errorText = errorMess;

    item.addEventListener('change', handleChange);
  }
});
