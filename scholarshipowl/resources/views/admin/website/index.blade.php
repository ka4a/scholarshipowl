@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Menu</p>
						
						<p><a class="ajax-link" href="/admin/website/settings">General Settings</a></p>
						<p><a class="ajax-link" href="#">My Profile</a></p>
						<p><a class="ajax-link" target="_blank" href="{{ URL::to('/')}}">View Website</a></p>
						<p><a class="MailTemplatePreview" href="#">View Mail Template</a></p>
						<p><a class="ajax-link" href="#">Logout</a></p>
						<p><a class="ajax-link" href="/admin/website/commands">Commands</a></p>
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
						
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
