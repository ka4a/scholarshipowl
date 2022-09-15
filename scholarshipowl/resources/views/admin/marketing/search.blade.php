@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/marketing-system?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
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
				<form method="get" action="/admin/marketing/search" class="form-horizontal">
					<fieldset>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Marketing System</label>
							<div class="col-sm-6">
								{{ Form::select('marketing_system_id[]', $options['marketing_systems'], $search['marketing_system_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
                            <label class="col-sm-3 control-label">Transaction ID</label>
                            <div class="col-sm-3">
                                {{ Form::text('transaction_id', $search['transaction_id'], array("class" => "form-control")) }}
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-3 control-label">Offer ID</label>
                            <div class="col-sm-3">
                                {{ Form::text('offer_id', $search['offer_id'], array("class" => "form-control")) }}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Affiliate ID</label>
                            <div class="col-sm-3">
                                {{ Form::text('affiliate_id', $search['affiliate_id'], array("class" => "form-control")) }}
                            </div>
                        </div>
                        
                        <hr />
						
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Conversion Date From</label>
                            <div class="col-sm-3">
                                {{ Form::text('conversion_date_from', $search['conversion_date_from'], array("class" => "form-control date_picker")) }}
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-3 control-label">Conversion Date To</label>
                            <div class="col-sm-3">
                                {{ Form::text('conversion_date_to', $search['conversion_date_to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-calendar-o"></i>
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
							<th>Full Name</th>
							<th>Marketing System</th>
							<th>Transaction ID</th>
							<th>Offer ID</th>
							<th>Affiliate ID</th>
							<th>Conversion Date</th>
						</tr>
					</thead>
					
					<tbody>
						@foreach ($data as $accountId => $entity)
							<tr>
								<td><a href="/admin/accounts/view?id={{ $accountId }}">{{ $entity->getAccount()->getProfile()->getFullName() }}</a></td>
								<td>{{ $entity->getMarketingSystem() }}</td>
								<td>{{ $entity->getHasOffersTransactionId() }}</td>
								<td>{{ $entity->getHasOffersOfferId() }}</td>
								<td>{{ $entity->getHasOffersAffiliateId() }}</td>
								<td>{{ $entity->getConversionDate() }}</td>
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
		<p align="right">Export ({{ $count }}): <a href="/admin/export/marketing-system?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

@stop
