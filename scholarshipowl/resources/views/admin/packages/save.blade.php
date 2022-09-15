@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Placeholders</span>
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
                            <th>Placeholder</th>
                            <th>Description</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($ph as $key => $phData)
						<tr>
							<td>*|{{$key}}|*</td>
							<td>{{$phData['description']}}</td>
							<td>{{$phData['value']}}</td>
						</tr>
						@endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Package</span>
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
				<form method="post" action="/admin/packages/post-save" class="form-horizontal ajax_form" id="SavePackageForm">
					{{ Form::token() }}
					{{ Form::hidden('package_id', $package->getPackageId()) }}

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-6">
								{{ Form::text('name', $package->getName(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Alias</label>
							<div class="col-sm-6">
								{{ Form::text('alias', $package->getAlias(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Price</label>
							<div class="col-sm-3">
								{{ Form::text('price', $package->getPrice(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Discount price</label>
							<div class="col-sm-3">
								{{ Form::text('discount_price', $package->getDiscountPrice(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Priority</label>
                            <div class="col-sm-3">
                                {{ Form::select('priority', $options["priority"], $package->getPriority(), array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>
						<hr/>

						<div class="form-group">
							<label class="col-sm-3 control-label">Freemium</label>
							<div class="col-sm-3">
								{{ Form::checkbox('is_freemium', 1, $package->isFreemium(), ['class' => 'form-control']) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Freemium recurrence period</label>
							<div class="col-sm-3">
								{{ Form::select('freemium_recurrence_period', $options["expiration_period_types"], $package->getFreemiumRecurrencePeriod(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Freemium recurrence value</label>
							<div class="col-sm-3">
								{{ Form::text('freemium_recurrence_value', $package->getFreemiumRecurrenceValue(), ['class' => 'form-control']) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Freemium credits</label>
							<div class="col-sm-3">
								{{ Form::text('freemium_credits', $package->getFreemiumCredits(), ['class' => 'form-control']) }}
							</div>
						</div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Contact Us Package (Elite Package)</label>
                            <div class="col-sm-3">
                                {{ Form::checkbox('is_contact_us', 1, $package->isContactUs(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Contact Us tab URL</label>
                            <div class="col-sm-3">
								{{ Form::text('contact_us_link', $package->getContactUsLink(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Free trial</label>
                            <div class="col-sm-3">
                                {{ Form::checkbox('free_trial', 1, $package->isFreeTrial(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Free Trial Period</label>
                            <div class="col-sm-3">
                                {{ Form::select('free_trial_period_type', $options["expiration_period_types"], $package->getFreeTrialPeriodType(), array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Free Trial Value</label>
                            <div class="col-sm-3">
                                {{ Form::text('free_trial_period_value', $package->getFreeTrialPeriodValue(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active</label>
							<div class="col-sm-3">
								{{ Form::select('is_active', $options["active"], $package->isActive(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Mobile Active</label>
							<div class="col-sm-3">
								{{ Form::select('is_mobile_active', $options["mobile_active"], $package->isMobileActive(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>


						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Scholarships</label>
							<div class="col-sm-3">
								{{ Form::select('scholarships', $options["scholarships"], $options["scholarships_selected"], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div id="PackageScholarshipsFixed">
							<div class="form-group">
								<label class="col-sm-3 control-label">Count</label>
								<div class="col-sm-3">
									{{ Form::text('scholarships_count', $package->getScholarshipsCount(), array("class" => "form-control")) }}
								</div>
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Expiration Type</label>
							<div class="col-sm-3">
								{{ Form::select('expiration_type', $options["expiration_types"], $package->getExpirationType(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div id="PackageExpirationTypeDate">
							<div class="form-group">
								<label class="col-sm-3 control-label">Date</label>
								<div class="col-sm-3">
									{{ Form::text('expiration_date', substr($package->getExpirationDate(), 0, 10), array("class" => "form-control date_picker")) }}
								</div>
							</div>
						</div>

						<div id="PackageExpirationTypePeriod">
							<div class="form-group">
								<label class="col-sm-3 control-label">Period Type</label>
								<div class="col-sm-3">
									{{ Form::select('expiration_period_type', $options["expiration_period_types"], $package->getExpirationPeriodType(), array("class" => "populate placeholder select2")) }}
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Period Value</label>
								<div class="col-sm-3">
									{{ Form::text('expiration_period_value_period', $package->getExpirationPeriodValue(), array("class" => "form-control")) }}
								</div>
							</div>
						</div>

                        <div id="PackageExpirationTypeRecurrent">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Recurrence Period</label>
                                <div class="col-sm-3">
                                    {{ Form::select('recurrence_period_type', $options["expiration_period_types"], $package->getExpirationPeriodType(), array("class" => "populate placeholder select2")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Period Value</label>
                                <div class="col-sm-3">
                                    {{ Form::select('expiration_period_value', $options["expiration_period_values"], $package->getExpirationPeriodValue(), array("class" => "populate placeholder select2")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gate2Shop - Rebilling Product ID</label>
                                <div class="col-sm-3">
                                    {{ Form::text('g2s_product_id', $package->getG2SProductId(), array("class" => "form-control")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gate2Shop - Rebilling Template ID</label>
                                <div class="col-sm-3">
                                    {{ Form::text('g2s_template_id', $package->getG2STemplateId(), array("class" => "form-control")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Braintree - Plan ID</label>
                                <div class="col-sm-3">
                                    {{ Form::text('braintree_plan', $package->getBraintreePlan(), array("class" => "form-control")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Recurly - Plan code</label>
                                <div class="col-sm-3">
                                    {{ Form::text('recurly_plan', $package->getRecurlyPlan(), array("class" => "form-control")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Stripe - Plan Id</label>
                                <div class="col-sm-3">
                                    {{ Form::text('stripe_plan', $package->getStripePlan(), array("class" => "form-control")) }}
                                </div>
                            </div>

							<div class="form-group">
                                <label class="col-sm-3 control-label">Stripe - Discount ID</label>
                                <div class="col-sm-3">
                                    {{ Form::text('stripe_discount_id', $package->getStripeDiscountId(), array("class" => "form-control")) }}
                                </div>
                            </div>

                        </div>

                        <hr />

						<div class="form-group">
                            <label class="col-sm-3 control-label">Is Issued Automatically</label>
                            <div class="col-sm-3">
                                {{ Form::select('is_automatic', $options["automatic"], $package->isAutomatic(), array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

						<hr />

                        <div class="form-group">
							<label class="col-sm-3 control-label">Summary description</label>
							<div class="col-sm-6">
								{{ Form::textarea('summary_description', $package->getSummaryDescription(), array("class" => "form-control")) }}
							</div><br>
							<div class="col-sm-2">
								Generic Tags:
								<code class="tag"> [[first_billing_date]] </code>
								<code class="tag"> [[billing_amount]] </code>
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-6">
								{{ Form::textarea('description', $package->getDescription(), array("class" => "form-control")) }}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">General Message</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('message', $package->getMessage(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Success title</label>
							<div class="col-sm-6">
								{{ Form::text('success_title', $package->getSuccessTitle(), array("class" => "form-control")) }}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Success Message</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('success_message', $package->getSuccessMessage(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Button CSS styles</label>
                            <div class="col-sm-6">
                                {{ Form::text('button_css', $package->getElementCSS('button'), array("class" => "form-control")) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Button Text</label>
                            <div class="col-sm-6">
                                {{ Form::text('button_content', $package->getElementContent('button'), array("class" => "form-control")) }}
                            </div>
                        </div>


						<div class="form-group">
                            <label class="col-sm-3 control-label">Popup CTA button text</label>
                            <div class="col-sm-6">
                                {{ Form::text('popup_cta_button', $package->getPopupCtaButton(), array("class" => "form-control")) }}
                            </div>
                        </div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Renewal Notice Email for states:</label>
							<div class="col-sm-6">
								<ul class="list-group hidden-xs">
									@foreach ($statesList as $item)
										<li class="list-group-item">
											{{ $item }}
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save Package</a>
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
				<a href="/admin/packages/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
