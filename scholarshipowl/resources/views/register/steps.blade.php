<div class="register-steps">
	<div class="container">
		<div class="row">
			<ul class="center-block clearfix">
				@for($i = 1; $i <= 4; $i++)
				<li class="{{ $i <= $register_step ? 'active': ''}} {{$i == $register_step ? ' current':''}}">
					<a href="#">{{ $i }}</a>
				</li>
				@endfor
			</ul>
		</div>
	</div>
</div>
