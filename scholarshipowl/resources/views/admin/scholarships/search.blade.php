@extends("admin/base")
@section("content")

@can('access-route', 'export')
<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/scholarships?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
		<p align="right">Export ({{ $count }}): <a href="/admin/export/scholarships_public?{{ http_build_query($pagination['url_params']) }}">Public CSV</a></p>
	</div>
</div>
@endcan

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
				<form method="get" action="/admin/scholarships/search" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-6">
								{{ Form::text('title', $search['title'], array("class" => "form-control")) }}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-3">
                                {{ Form::select('status', $options['status'], $search['status'], array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Application Type</label>
							<div class="col-sm-3">
								{{ Form::select('application_type', $options['application_types'], $search['application_type'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active</label>
							<div class="col-sm-3">
								{{ Form::select('is_active', $options['active'], $search['is_active'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Recurrent</label>
							<div class="col-sm-3">
								{{ Form::select('is_recurrent', $options['recurrent'], $search['is_recurrent'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Deadline From</label>

							<div class="col-sm-3">
								{{ Form::text('expiration_date_from', $search['expiration_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Deadline To</label>

							<div class="col-sm-3">
								{{ Form::text('expiration_date_to', $search['expiration_date_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Free</label>
							<div class="col-sm-3">
								{{ Form::select('is_free', $options['free'], $search['is_free'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Amount Min.</label>

							<div class="col-sm-3">
								{{ Form::text('amount_min', $search['amount_min'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Amount Max.</label>

							<div class="col-sm-3">
								{{ Form::text('amount_max', $search['amount_max'], array("class" => "form-control")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Up To Min.</label>

							<div class="col-sm-3">
								{{ Form::text('up_to_min', $search['up_to_min'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Up To Max.</label>

							<div class="col-sm-3">
								{{ Form::text('up_to_max', $search['up_to_max'], array("class" => "form-control")) }}
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
					<i class="fa fa-university"></i>
					<span>Results ({{ $count }})</span>
                    <a class="btn btn-success pull-right" href="{{ route('admin::scholarships.maintain') }}">Maintain</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content">
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Title</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>R</th>
							<th>Amount</th>
							<th>Free</th>
							<th>Requirements</th>
							<th>#</th>
							<th>Deadline</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@php
							$statuses = \App\Entity\ScholarshipStatus::repository()->findAll();
							/** @var \App\Entity\ScholarshipStatus $st */
							$statusesById = [];
							foreach ($statuses as $k => $st) {
								$statusesById[$st->getId()] = $st->getName();

							}
							unset($statuses);
						@endphp
						@foreach ($scholarships as $id => $scholarship)
							<tr>
								<td>
									@php
										$title = $scholarship->getTitle();
										if ($scholarship->getApplicationType() === \App\Entity\Scholarship::APPLICATION_TYPE_SUNRISE) {
										    $externalId = $scholarshipEntities[$id]->getExternalScholarshipId();
										    $externalTemplateId = $scholarshipEntities[$id]->getExternalScholarshipTemplateId();
										    $tip = "title=\"external id: $externalId external template id: {$externalTemplateId}\"";
											$title .= " <span {$tip} class=\"translucent\">(sunrise)</span>";
										}
									@endphp
									@if (!$scholarship->isActive())<strike>@endif
									<a target="_blank" href="{{ $scholarship->getUrl() }}">{!! $title !!}</a>
									@if (!$scholarship->isActive())</strike>@endif
								</td>

                                <td>
                                    {{ $statusesById[$scholarship->getStatus()] }}
                                </td>

                                <td>
									@if ($scholarship->getApplicationType() == "email")
										Email
									@elseif ($scholarship->getApplicationType() == "online")
										Online
									@elseif ($scholarship->getApplicationType() == "none")
										Only Database
									@endif
								</td>

								<td>{{ $scholarship->getIsRecurrent() ? 'Yes' : 'No' }}</td>
								<td>{{ $scholarship->getAmount() }}</td>
								<td>@if($scholarship->isFree()) {{ 'Yes' }} @else {{ 'No' }} @endif</td>
								<td>
                                    {{
                                         isset($scholarshipEntities[$scholarship->getScholarshipId()]) ?
                                            $scholarshipEntities[$scholarship->getScholarshipId()]->getRequirements()->count() : ''
                                    }}
                                </td>
								<td>{{ @$applied_counts[$id] }}</td>

								<td>
									@if ($scholarship->isExpired())<strike>@endif
									{{ format_date($scholarship->getExpirationDate()) }}
									@if ($scholarship->isExpired())</strike>@endif
								</td>

								<td>
									<div class="btn-group">
										<!-- <a class="btn btn-primary" href="javascript:void(0);"><i class="fa fa-university fa-fw"></i> Scholarship</a>  -->
										<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
											<span class="fa fa-caret-down"></span>
										</a>

										<ul class="dropdown-menu">
											<li><a href="/admin/scholarships/view?id={{ $id }}"><i class="fa fa-search fa-fw"></i> View Scholarship</a></li>
                                            <li><a href="{{ $scholarship->getPublicUrl() }}" target="_blank"><i class="fa fa-web fa-fw"></i>Open Webpage</a></li>
                                            @can('access-route', 'scholarships.edit')
											<li><a href="/admin/scholarships/save?id={{ $id }}"><i class="fa fa-pencil fa-fw"></i> Edit Scholarship</a></li>
											<li><a href="/admin/scholarships/copy?id={{ $id }}"><i class="fa fa-star fa-fw"></i> Copy Scholarship</a></li>
                                            @endcan
											<li><a href="/admin/scholarships/test?id={{ $id }}"><i class="fa fa-code fa-fw"></i> Test Scholarship</a></li>
                                            @can('access-route', 'scholarships.edit')
											<li>
												<a 	href="#"
													data-delete-url="/admin/scholarships/delete?id={{ $id }}"
													data-delete-message="Delete Scholarship '{{ $scholarship->getTitle() }}' (ID={{ $id }}) ?"
													title="Delete Scholarship"
													class="DeleteScholarshipButton">
													<i class="fa fa-file-text fa-fw"></i> Delete Scholarship
												</a>
											</li>
                                            @endcan
										</ul>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/scholarships?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
		<p align="right">Export ({{ $count }}): <a href="/admin/export/scholarships_public?{{ http_build_query($pagination['url_params']) }}">Public CSV</a></p>
	</div>
</div>

@stop
