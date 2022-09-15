@extends("admin/base")
@section("content")

<script>
window.onload = function () {
  	var btn = $("#run-command-btn");
  	var resultContainer = $("#command-result");
  	var inp = $("#inp-cmd");

  	btn.click(function() {
		$(this).addClass('loader-btn');
		$(this).attr('disabled', true);
		resultContainer.html('');

		if (!!window.EventSource) {
			var url = '/admin/website/commands?event-listener=1&cmd='+inp.val();
			var source = new EventSource(url);

			source.addEventListener('message', function(e) {
				var data = resultContainer.html();
				if (e.data === 'END-OF-STREAM') {
					source.close();
					btn.removeClass('loader-btn');
					btn.attr('disabled', false);
					resultContainer.html(data + '<br />Command execution finished');
				} else {
					resultContainer.html(data + '<br />' + e.data);
				}
			}, false);

			source.addEventListener('open', function(e) {

			}, false);

			source.addEventListener('error', function(e) {
				if (e.readyState == EventSource.CLOSED) {
					console.log('Connection was closed because of an error');
				}
			}, false);
		} else {
		  alert('Your browser does not support Server-Sent Events');
		}
  	});

}
</script>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-content">
				<p class="page-header">Commands</p>
				<form action="/admin/website/commands" class="form-horizontal ajax_form" method="post">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-12">Enter a command to run</label>

							<div class="col-sm-6">
								{{ Form::textarea('command', '', [
									'id' => 'inp-cmd',
									'rows' => 1,
									'cols' => 150,
									'placeholder' => 'e.g. application:send',
									'style' => 'padding: 5px 10px;'
								]) }}
							</div>

							<div class="col-sm-12">
								<a href="#" class="btn btn-primary" id="run-command-btn">Run</a>
							</div>
						</div>
					</fieldset>
				</form>
				<div  id="command-result" style="white-space: pre-line"></div>
			</div>
		</div>
	</div>
</div>


@stop
