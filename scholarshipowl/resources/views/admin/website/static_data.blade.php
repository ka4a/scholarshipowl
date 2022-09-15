@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-6">
		@include ("admin/website/static_data_box", array("title" => "Account Type", "data" => $static_data["AccountType"]))
		@include ("admin/website/static_data_box", array("title" => "Account Status", "data" => $static_data["AccountStatus"]))
		@include ("admin/website/static_data_box", array("title" => "Citizenship", "data" => $static_data["Citizenship"]))
		@include ("admin/website/static_data_box", array("title" => "Ethnicity", "data" => $static_data["Ethnicity"]))
		@include ("admin/website/static_data_box", array("title" => "Country", "data" => $static_data["Country"]))
		@include ("admin/website/static_data_box", array("title" => "State", "data" => $static_data["State"]))
	</div>
	
	<div class="col-xs-12 col-sm-6">
		@include ("admin/website/static_data_box", array("title" => "School Level", "data" => $static_data["SchoolLevel"]))
		@include ("admin/website/static_data_box", array("title" => "Degree", "data" => $static_data["Degree"]))
		@include ("admin/website/static_data_box", array("title" => "Degree Type", "data" => $static_data["DegreeType"]))
		@include ("admin/website/static_data_box", array("title" => "Career Goal", "data" => $static_data["CareerGoal"]))
		@include ("admin/website/static_data_box", array("title" => "Field", "data" => $static_data["Field"]))
	</div>
</div>


@stop
