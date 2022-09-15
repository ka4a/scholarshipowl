@extends("admin/base")
@section("content")

@if(false)
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search-plus"></i>
					<span>Filter Search</span>
				</div>

				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content" style="display: none;">
				<form method="get" action="/admin/winners/search" class="form-horizontal">

				</form>
			</div>
		</div>
	</div>
</div>
@endif

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-trophy"></i>
					<span>Results ({{ $count }})</span>
				</div>

				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content">
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Account ID</th>
							<th>Scholarship ID</th>
							<th>Scholarship Title</th>
							<th>Date</th>
                            <th>Amount</th>
                            <th>Winner Name</th>
                            <th>Is Published</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
 						/** @var \App\Entity\Winner $winner */
						?>
						@foreach ($result as $winner)
							<tr>
								<td>
									@php
										$accountId = $winner->getAccount() ? $winner->getAccount()->getAccountId() : '';
									@endphp
									@if($accountId)
										<a href="{{ route('admin::accounts.view', ['id' => $accountId]) }}" target="_blank">
											{{ $accountId }}
										</a>
									@endif
								</td>
								<td>
									@php
										$scholarshipId = $winner->getScholarship() ? $winner->getScholarship()->getScholarshipId() : '';
									@endphp
									@if($scholarshipId)
										<a href="{{ route('admin::scholarships.view', ['id' => $scholarshipId]) }}" target="_blank">
											{{ $scholarshipId }}
										</a>
									@endif
								</td>
								<td>{{ $winner->getScholarshipTitle()}}</td>
								<td>{{ $winner->getWonAt()->format('M Y') }}</td>
								<td>$ {{ $winner->getAmountWon() }}</td>
								<td>{{ $winner->getWinnerName() }}</td>
								<td>
									@if($winner->getPublished())
										<i class="fa fa-check" aria-hidden="true"></i>
									@endif
								</td>
								<td>
									<a class="btn btn-primary" href=" {{ route('admin::winners.view', ['id' => $winner->getId()]) }}">View</a>
                                    @can('access-route', 'winners.edit')
									<a class="btn btn-warning" href="{{ route('admin::winners.edit', ['id' => $winner->getId()]) }}">Edit</a>
                                    @endcan
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include (
					'admin/common/pagination',
					[
						'page' => $pagination['page'],
						'pages' => $pagination['pages'],
						'url' => $pagination['url'],
						'url_params' => $pagination['url_params']
					]
				)
			</div>
		</div>
	</div>
</div>

@stop
