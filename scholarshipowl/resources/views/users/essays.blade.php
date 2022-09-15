@extends('base')

@section("styles")
    {!! HTML::style("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css") !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle9') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')


<section>
	<div class="blue-bg">
		<div class="container">
			<div class="row">
				<div class="text">
					<h2 class="title text10">Essays</h2>
					<p class="description text30">
						Complete the essays to finish application. You can save your work at any time and continue later.
					</p>
				</div>
			</div>
		</div>
	</div>
</section>


<section id="essays" class="blueBg">
	<form method="post" action="" id="EssayForm" class="ajax_form" data-essays-count="{{ count($essays) }}">
		{{ Form::token() }}

		<div id="EssayRequirementsValues">
			<input type="hidden" name="EssayMinWords" value="" />
			<input type="hidden" name="EssayMaxWords" value="" />
			<input type="hidden" name="EssayMinCharacters" value="" />
			<input type="hidden" name="EssayMaxCharacters" value="" />
		</div>

		<div class="container">
			<div class="row center-block">
				<div class="col-md-8" id="EssayEdit">
					<div class="form-group">
						<select name="essayTitles" id="EssaySelect" class="" title="Select Essay">
 							<option value="0">--- Select Essay ---</option>

							@foreach ($essays as $essayId => $essay)
								<option value="{{ $essayId }}" {{ ($essayId == $selectedEssayId)?"selected=\"selected\"":"" }}>{{ $essay->getTitle() }}</option>
							@endforeach
						</select>
					</div>

					<div>
						<p id="EssayDescription"></p>
					</div>

					<div>
						<textarea name="essay" id="EssayText" cols="50" rows="10" class="form-control"></textarea>
						<p id="EssayTextParsed"></p>
					</div>

					<div class="essaysButtons row visible-xs visible-sm">
						<div class="col-xs-6">
							<div class="button-wrapper">
								<a class="more-button save-changes text-uppercase EssaySaveButton NotEditable" href="#">Save Essay</a>
							</div>
						</div>

						<div class="col-xs-6">
							<div class="button-wrapper">
								<input value="done" type="submit" class="ApplyNowButton more-button save-changes EssaysDone" />
							</div>
						</div>

						<div class="clearfix"></div>
					</div>
				</div>


				<div class="col-md-4">
					<div class="">
						<div class="upsale-wrapper center-block">
							<div class="col-sm-6 col-md-12">
								<div class="upsale">
									<div class="bars">
										<div class="title" id="ScholarshipData"></div>
										<div class="text" id="EssayRequirements"></div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-12">
								<div class="upsale">
									<div class="bars">
										<div class="title">Essays Completed</div>
										<div class="text">
											<span id="EssaysSaved" class="number">{{ count($savedEssays) }}</span> out of <span id="total" class="number">{{ count($essays) }}</span><br>
											<span id="EssaysSavedAmount" class="number">${{ $savedAmount }}</span> out of <span id="" class="number">${{ $totalAmount }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="clearfix"></div>

				<div class="essaysButtons hidden-xs hidden-sm clearfix">
					<div class="col-sm-5 col-sm-offset-1">
						<div class="button-wrapper">
							<a class="more-button save-changes text-uppercase EssaySaveButton NotEditable" href="#" disabled="disabled">Save Essay</a>
						</div>
					</div>

					<div class="col-sm-5">
						<div class="button-wrapper">
							<a href="{{ url("my-applications") }}" class="ApplyNowButton more-button save-changes EssaysDone">Done</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>


@include('includes/popup')
@include('includes/leaving')

@stop
