<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>ScholarshipOwl - Admin</title>
		
		<meta name="description" content="description">
		<meta name="author" content="ScholarshipOwl - Admin">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link href='https://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
		
		{{ HTML::style("admin/plugins/bootstrap/bootstrap.css") }}
		{{ HTML::style("admin/css/font-awesome-4.2.0/css/font-awesome.min.css") }}
		{{ HTML::style("admin/css/style.css") }}
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="https://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="https://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
<body>

<div class="container-fluid">
	<div id="page-login" class="row">
		<form action="/admin/post-login" method="post">
			{{ Form::token() }}
			
			<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
				<div class="box">
					<div class="box-content" style="padding-bottom: 25px">
						<div class="text-center">
							<h3 class="page-header">ScholarshipOwl - Admin</h3>
						</div>
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="text" class="form-control" name="email" />
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control" name="password" />
						</div>

						<div class="admin-login-error" id="login-error"></div>

						<div class="text-center">
							<a href="#" class="btn btn-primary" id="LoginButton">Sign in</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


{{ HTML::script("admin/plugins/jquery/jquery-2.1.0.min.js") }}
{{ HTML::script("common/js/core.js") }}
{{ HTML::script("admin/js/classes/common.js") }}
{{ HTML::script("admin/js/classes/main.js") }}

</body>

