@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Award</span>
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
				<form method="post" action="/admin/refer-a-friend/awards/post-save" class="form-horizontal ajax_form SaveReferralAwardForm" id="SaveReferralAwardForm">
					{!! Form::token() !!}
					{!! Form::hidden('referral_award_id', $award->getReferralAwardId()) !!}

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-6">
								{!! Form::text('name', $award->getName(), array("class" => "form-control")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Type</label>
							<div class="col-sm-3">
								{!! Form::select('referral_award_type_id', $options["award_types"], is_null($award->getReferralAwardType()) ? "" : $award->getReferralAwardType()->getReferralAwardTypeId(), array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<div id="AwardTypeReferrals">
							<div class="form-group">
								<label class="col-sm-3 control-label">Referrals Number</label>
								<div class="col-sm-6">
									{!! Form::text('referrals_number', $award->getReferralsNumber(), array("class" => "form-control")) !!}
								</div>
							</div>
						</div>

						<div id="AwardTypeShares">
							<div class="form-group">
								<label class="col-sm-3 control-label">Share Number</label>
								<div class="col-sm-6">
									@foreach($options["referralChannels"] as $referralChannel)
										<div class="col-sm-5">{{ $referralChannel }}</div>
										<div class="col-sm-7">
											{!! Form::text($referralChannel."_share_number", isset($options["shareNumber"][$referralChannel])?$options["shareNumber"][$referralChannel]:0, array("class" => "form-control")) !!}
										</div>
									@endforeach
								</div>
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active</label>
							<div class="col-sm-3">
								{!! Form::select('is_active', $options["active"], $award->isActive(), array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Referral Package</label>
							<div class="col-sm-3">
								{!! Form::select('referral_package_id', $options["packages"], is_null($award->getReferralPackage()) ? '' : $award->getReferralPackage()->getPackageId(), array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Referred Package</label>
							<div class="col-sm-3">
								{!! Form::select('referred_package_id', $options["packages"], is_null($award->getReferredPackage()) ? '' : $award->getReferredPackage()->getPackageId(), array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-6">
								{!! Form::textarea('description', $award->getDescription(), array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Redirect Description</label>
							<div class="col-sm-6">
								{!! Form::textarea('redirect_description', $award->getRedirectDescription(), array("class" => "form-control")) !!}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save Award</a>
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
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/refer-a-friend/awards" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
