@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-sm-12">
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
						<form method="get" action="" class="form-horizontal">
							<fieldset>
								<div class="form-group">
									{{ Form::label("usertype", "User Type", array("class" => "col-sm-3 control-label")) }}
									
									<div class="col-sm-6">
										{{Form::select("usertype", $options["usertype"], $search["usertype"], array("class" => "populate placeholder select2"))}}
									</div>
								</div>
							
								<div class="form-group">
									{{ Form::label('state', 'State', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('state', $options['state'], $search['state'], array('class' => 'populate placeholder select2')) }}
									</div>
								</div>
								
								<div class="form-group">
									{{ Form::label('major[]', 'Major', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('major[]', $options['major'], $search['major'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label("citizen", "Citizen", array("class" => "col-sm-3 control-label")) }}
									
									<div class="col-sm-6">
										{{Form::select("citizen", $options["citizen"], $search["citizen"], array("class" => "populate placeholder select2"))}}
									</div>
								</div>
								
								<div class="form-group">
									{{ Form::label("sex", "Sex", array("class" => "col-sm-3 control-label")) }}
									
									<div class="col-sm-6">
										{{Form::select("sex", $options["sex"], $search["sex"], array("class" => "populate placeholder select2"))}}
									</div>
								</div>
								
								<div class="form-group">
									{{ Form::label("age", "Age", array("class" => "col-sm-3 control-label")) }}
									
									<div class="col-sm-6">
										{{Form::text("age", $search["age"], array("class" => "form-control input-md"))}}
									</div>
								</div>
							
								<hr />
							
								<div class="form-group">
									{{ Form::label('career[]', 'Career', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('career[]', $options['career'], $search['career'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('interest[]', 'Interest', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('interest[]', $options['interest'], $search['interest'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('disability[]', 'Disability', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('disability[]', $options['disability'], $search['disability'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('athletics[]', 'Athletics', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('athletics[]', $options['athletics'], $search['athletics'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('religion[]', 'Religion', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('religion[]', $options['religion'], $search['religion'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('race[]', 'Race', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('race[]', $options['race'], $search['race'], array('class' => 'populate placeholder select2', 'multiple' => 'multiple')) }}
									</div>
								</div>
								
								<hr />
								
								<div class="form-group">
									{{ Form::label('membership', 'Membership', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('membership', $options['membership'], $search['membership'], array('class' => 'populate placeholder select2')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('military', 'Military', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('military', $options['military'], $search['military'], array('class' => 'populate placeholder select2')) }}
									</div>
								</div>

								<div class="form-group">
									{{ Form::label('circumstance', 'Special Circumstance', array('class' => 'col-sm-3 control-label')) }}
									
									<div class="col-sm-6">
										{{ Form::select('circumstance', $options['circumstance'], $search['circumstance'], array('class' => 'populate placeholder select2')) }}
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
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<div class="box-name">
							<i class="fa fa-university"></i>
							<span>Results ({{ count($scholarships) }})</span>
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
					
					<div class="box-content">
						<table class="table table-bordered table-striped table-hover table-heading">
							<thead>
								<tr>
									<th>Title</th>
									<th>Patron</th>
									<th>Deadline</th>
									<th>Amount</th>
									<th>Actions</th>
								</tr>
							</thead>
					<tbody>
						@foreach ($scholarships as $scholarship)
							<tr>
								<td><a href="/admin/scholarships/super-college/view?uuid={{ $scholarship['S_UUID'] }}" target="_blank">{{ $scholarship["SCHOL_NM"] }}</a></td>
								<td>{{ $scholarship["PATRON_NM"] }}</td>
								<td>{{ $scholarship["DEADLINE"] }}</td>
								<td>{{ $scholarship["AMOUNT"] }}</td>
								<td><a class="btn btn-success" href="/admin/scholarships/super-college/view?uuid={{ $scholarship['S_UUID'] }}" target="_blank">View</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	
@stop
