@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
	    <p align="right">Export ({{ $count }}): <a href="/admin/export/share-report">CSV</a></p>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-content">
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>User name</th>
							<th>First Shared Date</th>
							<th>Last Shared Date</th>
							<th>Total</th>
							@foreach ($options['referral_channels'] as $referral_channel)
							    <th>{{ $referral_channel }}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach ($shares as $share)
							<tr>
								<td>{{ restricted_data($share["first_name"], true) }} {{ restricted_data($share["last_name"], true) }}</td>
								<td>{{ $share["first_date"] }}</td>
								<td>{{ $share["last_date"] }}</td>
								<td>{{ $share["total"] }}</td>
								@foreach ($options['referral_channels'] as $referral_channel)
                                    <td>{{ isset($share[$referral_channel])?$share[$referral_channel]:0 }}</td>
                                @endforeach
                            </tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>


@stop
