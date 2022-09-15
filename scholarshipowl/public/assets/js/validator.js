/*!
 * Validator
 * 
 */

$(document).ready(function() {
	$('#feedbackForm')

		.find('[name="checkYourChances"]')
			.selectpicker()
			.change(function(e) {
				// revalidate the option when it is changed
				$('#checkYourChances').bootstrapValidator('revalidateField', 'checkYourChances');
			})
			.end()
		
		.bootstrapValidator({
            excluded: ':disabled',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
    	    },
	        fields: {
	            name: {
	                group: '.input-group',
	                validators: {
	                    notEmpty: {
	                        message: 'Please fill in your name'
	                    },
	                    stringLength: {
	                    	min: 6,
	                        max: 200,
	                        message: 'The name must be more than 6 and less than 30 characters long'
	                    }
	                }
	            },
	            email: {
	                group: '.input-group',
	                validators: {
	                    notEmpty: {
	                        message: 'The email address is required and cannot be empty'
	                    },
	                    emailAddress: {
	                        message: 'The email address is not a valid'
	                    }
	                }
	            },
	            typeOfFeedback: {
	            	group: '.selectContainer',
	            	validators: {
                		notEmpty: {
                    		message: 'Please select one option'
                		}
            		}
	            }
       		}
		});
	});
