$(function() {
	
	// Eligibility Form Server Side Validator
	if ($('#frmChangePassword').length) {
		$("#frmChangePassword").on('submit',function(e) {
		    e.preventDefault();
		});
		
		$("#btnChangePassword").on( "click", function(e) {
			e.preventDefault();
			
			var $action = "/admin/change-password";
			var $token 	= $("input[name=_token]").val();
			
			var $id = $('[name=id]').val();
			var $password = $('[name=password]').val();
			var $retype_password = $('[name=retype_password]').val();
			
			$.ajax({
				type : 'POST',
				url : $action,
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: {
					id: $id,
					password: $password,
					retype_password: $retype_password
				},
				success: function(ret) {
					if (ret.success) {
						alert(ret.success);
					}
					else if (ret.redirect) {
						location.href = ret.redirect;
					}
					else if (ret.error) {
						alert(ret.error);
					}
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		});
	};
	
	
	$('.scholarship-update-form').on('submit', function(e) {
		e.preventDefault();
		var data   = $(this).serialize();
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('action');
		var id     = $(this).find('[name=id]').val();
		var deadline = $(this).find('[name=deadline]').val();
		var title  = $(this).find('[name=title]').val();
		var awards = $(this).find('[name=awards]').val();
		var amount = $(this).find('[name=amount]').val();
		
		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				$('.scholarship-update-form .form-group').removeClass('has-error');
				if (ret.success) {
					$('#sch_deadline-' + id).text(deadline);
					$('#sch_title-' + id + ' a').text(title);
					$('#sch_amount-' + id).text(amount);
					$('#sch_awards-' + id).text(awards);
					$('#collapse-' + id).delay('300').collapse('hide');
				} else {
					$.each(ret.errors, function (k,v) {
						$('[name=' + k + ']').closest('.form-group').addClass('has-error')
					});
				}
			},
			fail: function(ret) {
				//
			}
		});
	});
	$('.scholarship-create-form').on('submit', function(e) {
		e.preventDefault();
		var data   = $(this).serialize();
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('action');
		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				$('.scholarship-create-form .form-group').removeClass('has-error');
				if (ret.success) {
					location.href = ret.redirect;
				} else {
					$.each(ret.errors, function (k,v) {
						$('[name=' + k + ']').closest('.form-group').addClass('has-error')
					});
				}
			},
			fail: function(ret) {
				//
			}
		});
	});

	$('[id^="collapse-fields-"]').on('submit', '.field-add-form', function(e) {
		e.preventDefault();
		var data   = $(this).serialize();
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('action');
		var schid  = $(this).find('[name=scholarship_id]').val();
		$.ajax({
			type: 'POST',
			url: action,
			data: data,
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				$('.field-add-form .form-group').removeClass('has-error');
				if (ret.success) {
					$('#collapse-fields-' + schid).find('.scholarship-fields-list').html(ret.html);
					$('.field-add-form')[0].reset();
					$('.field-add-form .message').text('Field successfully added');
				} else {
					$.each(ret.errors, function (k,v) {
						if (k == 'essay_titles') {
							k = 'essay_titles\\[\\]';
						}
						$('[name=' + k + ']').closest('.form-group').addClass('has-error')
					});
				}
			},
			fail: function(ret) {
				//
			}
		});
	});
	/*
	$('.admin-actions .btn-info').on('click', function(e) {
		//console.log(data);
		e.preventDefault();
		$('[id^="collapse-"]').collapse('hide');
		var data = $(this).attr('data-target');
		$(data).collapse('show');
	});
	*/
	$('[id^="collapse-fields-"]').on('click', '.sh-field-delete-button', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-field-id');
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('data-action');
		var schid = $(this).attr('data-schid');
		var flist  = $(this).closest('.scholarship-fields-list');
		var form = $('#collapse-add-field-' + schid + ' .field-add-form');

		$.ajax({
			type: 'POST',
			url: action,
			data: {id: id},
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				if (ret.success) {
					flist.html(ret.html);
					form[0].reset();
					form.find('[name^="id"]').val('');
				} else {
					//
				}
			},
			fail: function(ret) {
				//
			}
		});
		
	});
	$('[id^="collapse-fields-"]').on('click', '.sh-field-edit-button', function(e) {
		e.preventDefault();

		var id = $(this).attr('data-field-id');
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('data-action');
		var schid = $(this).attr('data-schid');
		var form = $('#collapse-add-field-' + schid + ' .field-add-form');
		$.ajax({
			type: 'GET',
			url: action,
			data: {id: id},
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				if (ret.success) {
					// clear form fields
					form[0].reset();
					form.find('[name^="id"]').val('');
					//populate form and expand it
					$.each(ret.data, function (k,v) {
						if (k == 'titles') {
							form.find('[name^="essay_titles"]').val(v);
						} else {
							form.find('[name=' + k + ']').val(v);
						}
					});
					$('#collapse-add-field-' + schid).collapse('show');
				} else {
					//
				}
			},
			fail: function(ret) {
				//
			}
		});
	});
	$('.site-fields').on('click', '.field-edit-button', function(e) {
		e.preventDefault();

		var id = $(this).attr('data-field-id');
		var token  = $('meta[name="csrf-token"]').attr('content');
		var action = $(this).attr('data-action');
		var form = $('#collapse-create-field .field-create-form');
		$.ajax({
			type: 'GET',
			url: action,
			data: {id: id},
			dataType: 'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', token);
			},
			success: function(ret) {
				if (ret.success) {
					// clear form fields
					form[0].reset();
					form.find('[name^="id"]').val('');
					//populate form and expand it
					$.each(ret.data, function (k,v) {
						form.find('[name=' + k + ']').val(v);
					});
					$('#collapse-create-field').collapse('show');
				} else {
					//
				}
			},
			fail: function(ret) {
				//
			}
		});
	});
});
