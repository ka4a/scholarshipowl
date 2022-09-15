@extends('base-landing')
@section('content')

@if (is_production())
	@if ($offerId === "30")
		<img src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=16" width="1" height="1" />
	@endif

	@if ($offerId === "32")
		<iframe src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=22" scrolling="no" frameborder="0" width="1" height="1"></iframe>
	@endif
@endif

<div class="centralized lp2">
	<div>
		<div class="text4">CHECK IF YOU <span class="bold">QUALIFY</span> FOR ONE OF</div>
		<div class="text5"><span>$</span></div>
		<div class="text6"> <span class="left"></span>WORTH OF <span class="right"></span></div>
		<div class="text7"></div>

		<div class="box">
			<div class="box_bg"></div>
			<div class="box_content">
				<input type="number" name="zip" placeholder="Zip Code">
				<div class="btn-group custom-select input-subject">
					<button class="btn select">Current School Level</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu ">
						<div class="scroll">
							@foreach($options['current_school_level'] as $option)
							<li><a href="#" value="{{$option}}">{{$option}}</a><li>
							@endforeach
						</div>
					</ul>
				</div>

				<div class="btn-group custom-select input-major">
					<button class="btn select">Intended Major</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu" toggle="ART-DESIGN">
						<div class="scroll">
							@foreach($options['major'] as $option)
							<li><a href="#" value="{{$option}}">{{$option}}</a><li>
							@endforeach
						</div>
					</ul>
				</div>

				<div class="btn-group custom-select input-degree">
					<button class="btn select">Degree</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span class="arrow"></span>
					</button>
					<ul class="dropdown-menu">
						<div class="scroll">
							@foreach($options['degree'] as $option)
							<li><a href="#" value="{{$option}}">{{$option}}</a><li>
							@endforeach
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

		var $zip = $('input[name=zip]').val();
		var $school_level = $('.input-subject > button.select').text().trim();
		var $major = $('.input-major > button.select').text().trim();
		var $degree = $('.input-degree > button.select').text().trim();

		$.ajax({
			type:'POST',
			url : '{{ action("AjaxController@postLanding")}}',
			data: {zip: $zip, current_school_level: $school_level, major: $major, degree: $degree},
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
