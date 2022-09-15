@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-code"></i>
					<span>Test Application Send Scholarship</span>
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
				<p><b>Data</b></p>
				<pre>{{ print_r($online_send["data"], true) }}</pre>
				<hr />
				
				@if ($online_send["hash"])
					<p><b>Response</b></p>
					<iframe src="/admin/scholarships/test?hash={{ $online_send["hash"] }}" width="100%" height="420px"></iframe>
					<hr />
				@endif
				
				<p><b>Request Headers</b></p>
				<pre>{{ print_r($online_send["request_headers"], true) }}</pre>
				<hr />
				
				<p><b>Response Headers</b></p>
				<pre>{{ print_r($online_send["response_headers"], true) }}</pre>
				<hr />
				
				@if (@$online_send["error_code"])
					<p><b>Error Code</b></p>
					<pre>{{ $online_send["error_code"] }}</pre>
					<hr />
				@endif
				
				@if (@$online_send["error_message"])
					<p><b>Error Message</b></p>
					<pre>{{ $online_send["error_message"] }}</pre>
				@endif
			</div>
		</div>
	</div>
</div>

@stop
