<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>ScholarshipOwl - Admin</title>

		<meta name="description" content="description">
		<meta name="author" content="ScholarshipOwl - Admin">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		{{ HTML::style('//fonts.googleapis.com/css?family=Righteous') }}
		{{ HTML::style('//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css') }}
		{{ HTML::style('//cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css') }}
		{{ HTML::style("admin/css/font-awesome-4.2.0/css/font-awesome.min.css") }}
		{{ HTML::style("admin/plugins/jquery-ui/jquery-ui.min.css") }}

		{!! \App\Extensions\AssetsHelper::getCSSBundle('admin1') !!}

		<link rel="shortcut icon" href="{{ url('/assets/img/favicon.ico') }}" type="image/vnd.microsoft.icon" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="https://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="https://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
<body>

<!--Start Header-->
<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2">
				<a href="/admin/dashboard">ScholarshipOwl</a>
			</div>

			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-4">
						<a href="#" class="show-sidebar">
						  <i class="fa fa-bars"></i>
						</a>

						<form method="get" action="/admin/scholarships/search">
							<div id="search">
								<input type="text" name="title" placeholder="search"/>
								<i class="fa fa-search"></i>
							</div>
						</form>
					</div>
					<div class="col-xs-4 col-sm-8 top-panel-right">
						<ul class="nav navbar-nav pull-right panel-menu">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
									<div class="avatar">
										<img src="/admin/img/male_avatar.jpg" class="img-rounded" alt="avatar" />
									</div>
									<i class="fa fa-angle-down pull-right"></i>
									<div class="user-mini pull-right">
										<span class="welcome">Welcome,</span>
										<span>{{ $user->getName() }}</span>
									</div>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="/admin/accounts/edit?id={{ $user->getAccountId() }}">
											<i class="fa fa-user"></i>
											<span>My Profile</span>
										</a>
									</li>
									<li>
										<a target="_blank" href="{{ URL::to('/') }}">
											<i class="fa fa-desktop"></i>
											<span>View Website</span>
										</a>
									</li>
									<li>
										<a href="/admin/logout">
											<i class="fa fa-power-off"></i>
											<span>Logout</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!--End Header-->


<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			@include('admin.sidebar')
		</div>

		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">
			<div id="ajax-content">
				@if ($breadcrumb)
					<div class="row">
						<div id="breadcrumb" class="col-md-12">
							<ol class="breadcrumb">
								@foreach ($breadcrumb as $key => $url)
									<li><a href="{{ $url }}">{{ $key }}</a></li>
								@endforeach
							</ol>
						</div>
					</div>
				@endif

				<div class="row" id="dashboard-header">
					<div class="col-xs-10 col-sm-12">
						<h3 class="page-header">{!! $title !!}</h3>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-10 col-sm-12">
                        @if (count($errors))
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('message'))
                            <div class="alert alert-success">{{ Session::get('message') }}</div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger">{{ Session::get('error') }}</div>
                        @endif
						<p id="msgNotification" class="
							@if (!empty($flash))
								@if ($flash["type"] == "ok")
									bg-common bg-success
								@elseif ($flash["type"] == "error")
									bg-common bg-danger
								@endif
							@endif
							">

							@if (!empty($flash))
								@if ($flash["type"] == "ok")
									{{ $flash["data"] }}
								@elseif ($flash["type"] == "error")
									@foreach ($flash["data"] as $error)
										{{ $error }} <br />
									@endforeach
								@endif
							@endif
						</p>
					</div>
				</div>

				@yield('content')
			</div>
		</div>
		<!--End Content-->
	</div>
</div>

<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js') }}

{{ HTML::script("admin/plugins/jquery/jquery-2.1.0.min.js") }}
{{ HTML::script("admin/plugins/jquery-ui/jquery-ui.min.js") }}

{{ HTML::script('//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js') }}
{{ HTML::script('//cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js') }}
{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js') }}

<!-- Include all compiled plugins (below), or include individual files as needed -->
{{ HTML::script("admin/plugins/bootstrap/bootstrap.min.js") }}
{{ HTML::script("admin/plugins/justified-gallery/jquery.justifiedgallery.min.js") }}
{{ HTML::script("admin/plugins/tinymce/tinymce.min.js") }}
{{ HTML::script("admin/plugins/tinymce/jquery.tinymce.min.js") }}
{{ HTML::script("admin/plugins/inputmask/jquery.inputmask.js") }}

{!! \App\Extensions\AssetsHelper::getJSBundle('admin1') !!}

</body>
</html>
