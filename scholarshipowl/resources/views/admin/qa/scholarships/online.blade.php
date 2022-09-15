@extends("admin/base")
@section("content")



<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-check-square-o"></i>
					<span>Test Online Scholarship</span>
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
				<form class="form-horizontal ajax_form">
					<fieldset>
						@foreach ($scholarship->getFields() as $field)
							<div class="form-group">
								<label class="col-sm-3 control-label">{{ $field->getName() }}</label>
								
								<div class="col-sm-6">
									@if ($field->getType() == "text")
										{{ Form::text($field->getName(), "", array("class" => "form-control")) }}
									@elseif ($field->getType() == "select")
										{{ Form::select($field->getName(), json_decode($field->getValue()), null, array("class" => "populate placeholder select2")) }}
									@elseif ($field->getType() == "radio")
									
									@elseif ($field->getType() == "checkbox")
									
									@elseif ($field->getType() == "area")
									
									@endif
								</div>
							</div>
						@endforeach
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
				@if ($scholarship->getScholarshipId() > 0)
					<a href="/admin/scholarships/view?id={{ $scholarship->getScholarshipId() }}"  class="btn btn-primary">View</a>
					<a href="/admin/scholarships/save?id={{ $scholarship->getScholarshipId() }}"  class="btn btn-primary">Edit</a>
					<a href="/admin/scholarships/copy?id={{ $scholarship->getScholarshipId() }}" title="Copy Scholarship" class="btn btn-warning">Copy</a>
					<a href="#" data-delete-url="/admin/scholarships/delete?id={{ $scholarship->getScholarshipId() }}" data-delete-message="Delete Scholarship ?" title="Delete Scholarship" class="btn btn-danger DeleteScholarshipButton">Delete</a>
				@endif
				
				<a href="/admin/scholarships/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop

