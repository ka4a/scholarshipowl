
@foreach ($forms as $i => $form)
	<h3>Form {{ $i + 1 }}</h3>
	
	<div style="border: 1px solid gray; padding: 10px;">
		<h4>Properties</h4>
		<p><b>Action: </b>{{ $form->action }}</p>
		<p><b>Method: </b>{{ $form->method }}</p>
		<hr />
		
		<h4>Fields</h4>
		{!! $form !!}
		
		<hr />
		<h4>Hidden Fields</h4>
		
		@foreach ($form->find("input[type=hidden]") as $hidden)
			<input name="{{ $hidden->name }}" value="{{ $hidden->value }}" disabled="disabled" /> ( {{ $hidden->name }} )
			<br />
		@endforeach
	</div>
	
	<hr />
@endforeach
