@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/refer-a-friend?{{ http_build_query($pagination["url_params"]) }}">CSV</a></p>
	</div>
</div>

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
				<form method="get" action="/admin/refer-a-friend/search" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Referral First Name</label>
							<div class="col-sm-6">
								{!! Form::text("referral_first_name", $search["referral_first_name"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referral Last Name</label>
							<div class="col-sm-6">
								{!! Form::text("referral_last_name", $search["referral_last_name"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referral Email</label>
							<div class="col-sm-6">
								{!! Form::text("referral_email", $search["referral_email"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referral Created Date From</label>
							<div class="col-sm-3">
								{!! Form::text("referral_created_date_from", $search["referral_created_date_from"], array("class" => "form-control date_picker")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referral Created Date To</label>
							<div class="col-sm-3">
								{!! Form::text("referral_created_date_to", $search["referral_created_date_to"], array("class" => "form-control date_picker")) !!}
							</div>
						</div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Referral Channel</label>
                            <div class="col-sm-3">
                                {!! Form::select("referral_channel", $options["referral_channels"], $search["referral_channel"], array("class" => "populate placeholder select2")) !!}
                            </div>
                        </div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred First Name</label>
							<div class="col-sm-6">
								{!! Form::text("referred_first_name", $search["referred_first_name"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred Last Name</label>
							<div class="col-sm-6">
								{!! Form::text("referred_last_name", $search["referred_last_name"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred Email</label>
							<div class="col-sm-6">
								{!! Form::text("referred_email", $search["referred_email"], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred Created Date From</label>
							<div class="col-sm-3">
								{!! Form::text("referred_created_date_from", $search["referred_created_date_from"], array("class" => "form-control date_picker")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred Created Date To</label>
							<div class="col-sm-3">
								{!! Form::text("referred_created_date_to", $search["referred_created_date_to"], array("class" => "form-control date_picker")) !!}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Search</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-users"></i>
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
							<th>Referral Full Name</th>
							<th>Referral Sign Up Date</th>
							<th>Referral Profile Completeness</th>
							<th>Referral Eligible Scholarships Count</th>
							<th>Referral Applications Count</th>
							<th>Referral Paid</th>
							<th>Referral Channel</th>
							<th>Referred Full Name</th>
							<th>Referred Sign Up Date</th>
							<th>Referred Profile Completeness</th>
							<th>Referred Eligible Scholarships Count</th>
							<th>Referred Applications Count</th>
							<th>Referred Paid</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($referrals as $referral)
							<tr>
								<td>
									<a href="/admin/accounts/view?id={{ $referral->getReferralAccount()->getAccountId() }}" target="_blank">
										{{ restricted_data($referral->getReferralAccount()->getProfile()->getFullName(), true) }}
									</a>
								</td>

								<td>{{ format_date($referral->getReferralAccount()->getCreatedDate()->format('Y-m-d h:i:s')) }}</td>
								<td>{{ $referral->getReferralAccount()->getProfile()->getCompleteness() }}</td>

								<td>
									@if (array_key_exists($referral->getReferralAccount()->getAccountId(), $eligibles))
										{{ count($eligibles[$referral->getReferralAccount()->getAccountId()]) }}
									@else
										0
									@endif
								</td>

								<td>
									@if (array_key_exists($referral->getReferralAccount()->getAccountId(), $applications))
										{{ $applications[$referral->getReferralAccount()->getAccountId()] }}
									@else
										0
									@endif
								</td>

								<td>
									@if ($referral->getReferralAccount()->getSubscriptions()->count() > 0)
										Yes
									@else
										No
									@endif
								</td>

								<td>
								    {{ $referral->getReferralChannel() }}
								</td>


								<td>
									<a href="/admin/accounts/view?id={{ $referral->getReferredAccount()->getAccountId() }}" target="_blank">
										{{ restricted_data($referral->getReferredAccount()->getProfile()->getFullName(), true) }}
									</a>
								</td>

								<td>{{ format_date($referral->getReferredAccount()->getCreatedDate()->format('Y-m-d h:i:s')) }}</td>
								<td>{{ $referral->getReferredAccount()->getProfile()->getCompleteness() }}</td>

								<td>
									@if (array_key_exists($referral->getReferredAccount()->getAccountId(), $eligibles))
										{{ count($eligibles[$referral->getReferredAccount()->getAccountId()]) }}
									@else
										0
									@endif
								</td>

								<td>
									@if (array_key_exists($referral->getReferredAccount()->getAccountId(), $applications))
										{{ $applications[$referral->getReferredAccount()->getAccountId()] }}
									@else
										0
									@endif
								</td>

								<td>
									@if ($referral->getReferredAccount()->getSubscriptions()->count() > 0)
										Yes
									@else
										No
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ("admin/common/pagination", array("page" => $pagination["page"], "pages" => $pagination["pages"], "url" => $pagination["url"], "url_params" => $pagination["url_params"]))
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/refer-a-friend?{{ http_build_query($pagination["url_params"]) }}">CSV</a></p>
	</div>
</div>

@stop
