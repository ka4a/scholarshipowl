@extends("admin/base")
@section("content")

@if ($winner)
<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<?php
			/** @var \App\Entity\Winner $winner */
			?>
			<div class="col-sm-4">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Winner info</p>
						@if($accountId = $winner->getAccountId())
							<p><b>Account ID: </b><a href={{route('admin::accounts.view', ['id' => $accountId])}}>{{ $accountId }}</a></p>
							<p><b>Email: </b>{{ $winner->getAccount()->getEmail() }}</p>
						@endif

						<p><b>Name: </b>{{ $winner->getWinnerName() }}</p>
						<p><img style="max-width: 100%" src="{{ $winner->getWinnerPhoto() }}" /></p>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-content">
							<p class="page-header">Scholarship Info</p>
                            @if ($scholarshipId = $winner->getScholarshipId())
								<p><b>ID: </b><a href={{route('admin::scholarships.view', ['id' => $scholarshipId])}}>{{ $scholarshipId }}</a></p>
                            @endif
							<p><b>Title: </b>{!! $winner->getScholarshipTitle() !!}</p>
							<p><b>Amount won: </b>$ {{ $winner->getAmountWon() }}</p>
							<p><b>Won at: </b>{{ $winner->getWonAt()->format('M Y') }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-content">
							<p class="page-header">Winner's Testimonial</p>
							<p>{!! $winner->getTestimonialText() !!}</p>
                            @if ($video = $winner->getTestimonialVideo())
                                <p><iframe  width="100%" src="{{ $video }}" frameborder="0" allowfullscreen></iframe></p>
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="{{route('admin::winners.search')}}" class="btn btn-default">Back To Search</a>
				<a href="{{route('admin::winners.edit', ['id' => $winner->getId()])}}" class="btn btn-warning">Edit</a>
			</p>
		</div>
	</div>
</div>
@else
<div class="row">
	<div class="col-xs-12">
	<p>Winner not found!</p>
		<div class="col col-12 pull-right">
			<p>
				<a href="{{route('admin::winners.search')}}" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>
@endif


@stop
