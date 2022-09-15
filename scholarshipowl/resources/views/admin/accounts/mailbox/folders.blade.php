@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-envelope-o"></i>
					<span>Folders</span>
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
				<div id="tabs">
					<ul>
						<li><a href="#tab-inbox">Inbox ({{ $unread["Inbox"] }}/{{ count($folders["Inbox"]) }})</a></li>
						<li><a href="#tab-sent">Sent ({{ $unread["Sent"] }}/{{ count($folders["Sent"]) }})</a></li>
					</ul>
					
					<div id="tab-inbox">
						@include ("admin/accounts/mailbox/folder", array("emails" => $folders["Inbox"]))
					</div>
					
					<div id="tab-sent">
						@include ("admin/accounts/mailbox/folder", array("emails" => $folders["Sent"]))
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/accounts/view?id={{ $accountId }}"  class="btn btn-primary">View Account</a>
			</p>
		</div>
	</div>
</div>

@stop
