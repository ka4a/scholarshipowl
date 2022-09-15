$(document).ready(function() {
    if ($('input[name=dob_mm]').length) {
        $('input[name=dob_yyyy]').inputmask('9999');
        $('input[name=dob_dd]').inputmask('99');
        $('input[name=dob_mm]').inputmask('99');
    }

	if($("#parent_phone_number_input").length) {
		$("#parent_phone_number_input").inputmask("(999) 999 - 9999", {
			"placeholder": "(   )     -     ",
			showMaskOnHover: false
		});
	}

    $(".ajax_form").on('submit', function(e) {
        e.preventDefault();
    });
});
