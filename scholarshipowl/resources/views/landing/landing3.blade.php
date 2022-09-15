@extends('base-landing')
@section('content')
<div class="centralized lp3">
	<div>
		<div class="text1">HOW MANY</div>
		<div class="text2"></div>
		<div class="text3">ARE YOU ELIGIBLE TO GET?</div>

		<div class="text4">
			I am
			<span class="popupInput input-age">
				<a href="#">16</a>
			</span>
			years old
		</div>
		<div class="text5">
			<span class="popupSelect input-school_level">
				<a href="#">{{head($options['current_school_level'])}}</a>
				<ul class="dropdown-menu">
					@foreach($options['current_school_level'] as $option)
					<li><a href="#">{{$option}}</a></li>
					@endforeach
				</ul>
			</span>

		</div>
		<div class="text5">
			<span class="popupSelect input-gender">
				<a href="#">{{head($options['gender'])}}</a>
				<ul class="dropdown-menu">
					@foreach($options['gender'] as $option)
					<li><a href="#">{{$option}}</a></li>
					@endforeach
				</ul>
			</span>
		</div>
		<a href="#" class="btn1"></a>
	</div>
</div>
@stop
@section('scripts')
<script>
	$(function () {
		$('.input-age').on('change', function (event, new_value) {
			$('.input-age > a').html(new_value)
		})
		$('.input-school_level').on('change', function (event, new_value) {
			$('.input-school_level > a').html(new_value)
		})
		$('.input-gender').on('change', function (event, new_value) {
			$('.input-gender > a').html(new_value)
		})
		$('.input-gender').on('change', function (event, new_value) {
			$('.input-gender > a').html(new_value)
		})
	
		$('a.btn1').click(function(e) {

		e.preventDefault();

		var $token = $('meta[name="csrf-token"]').attr('content');

		var $age = $('.input-age > a').text().trim();
		var $school_level = $('.input-school_level > a').text().trim();
		var $gender = $('.input-gender > a').text().trim();

		$.ajax({
			type:'POST',
			url : '{{ action("AjaxController@postLanding")}}',
			data: {age: $age, gender: $gender, current_school_level: $school_level},
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
