@extends('base-landing')
@section('content')
<div class="centralized lp1">
	<div>
		<div class="text1">HOW MANY</div>
		<div class="text2"></div>
		<div class="text3">ARE YOU ELIGIBLE TO GET?</div>
		<div class="box">
			<div class="box_bg"></div>
			<div class="box_content">
				<!-- <div class="zip error">Error message goes here</div> -->
				<input placeholder="Your age" name="age" type="number" step="1" min="1944" max="2014">
				<div class="btn-group custom-select input-subject">
					<button class="btn select">Current School Level</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu">
						<div class="scroll">
							@foreach($options['current_school_level'] as $option)
							<li><a href="#" value="{{$option}}">{{$option}}</a><li>
							@endforeach
						</div>
					</ul>
				</div>
				<div class="btn-group custom-select input-field_of_study">
					<button class="btn select">Field of Study</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu ">
						<div class="scroll">
							@foreach($options['field_of_study_select'] as $option)
							<li><a href="#" value="{{$option}}">{{$option}}</a></li>
							@endforeach
						</div>
					</ul>
				</div>
				<div class="btn-group custom-select input-degree">
					<button class="btn select">Gender</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu">
						<div class="scroll">
							<li><a href="#" value="Female">Female</a></li>
							<li><a href="#" value="Male">Male</a></li>
						</div>
					</ul>
				</div>
				<a href="#" class="btn2"></a>
			</div>
			<div class="box_info">
			</div>
		</div>
		<a href="#" class="btn1"></a>
	</div>
</div>
@stop
@section('scripts')
<script>
$(function() {
	$('a.btn1').click(function(e) {
		e.preventDefault();

		var $token = $('meta[name="csrf-token"]').attr('content');

		var $age = $('input[name=age]').val();
		var $gender = $('.input-degree > button.select').text().trim();
		var $field_of_study = $('.input-field_of_study > button.select').text().trim();
		var $school_level= $('.input-subject > button.select').text().trim();

		$.ajax({
			type:'POST',
			url : '{{ action("AjaxController@postLanding")}}',
			data: {age: $age, gender: $gender, current_school_level: $school_level, field_of_study_select: $field_of_study},
			dataType:'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', $token);
			},
			success: function(ret) {
				if (ret.success) {
					location.href = ret.redirect;
				}
			},
			fail: function(data) {
				//
			}
		});
	});
});
</script>
@stop
