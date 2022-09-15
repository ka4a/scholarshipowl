@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Menu</p>
						
						<p><a href="/admin/popup/save">Add Popup</a></p>
						<p><a href="/admin/popup/search">Search Popups</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-sm-4">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Customer Support</p>
						
						<p>Add and configure popups that appear before and/or after payment actions.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
