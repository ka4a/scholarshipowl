@extends("admin/base")
@section("content")
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-cog"></i>
					<span>AB Tests configurations</span>
				</div>
			</div>
			<div class="box-content">
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Id</th>
							<th>Name</th>
                            <th>Active</th>
							<th>Feature Set</th>
							<th>Config</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($tests as $test)
							<tr>
								<td>{{ $test->getId() }}</td>
                                <td>{{ $test->getName() }}</td>
                                <td>{{ $test->isEnabled() ? 'Yes' : 'No' }}</td>
                                <td>{{ $test->getFeatureSet() }}</td>
                                <td>{{ json_encode($test->getConfig()) }}</td>
								<td>
                                    <a class="btn btn-warning" href="{{ route('admin::features.ab_tests.edit', $test->getId()) }}">Edit</a>
                                    <a class="btn btn-danger" data-confirm-message="{{ sprintf('Are you sure want to delete \'%s\' ab test?', $test)  }}" href="{{ route('admin::features.ab_tests.delete', $test->getId()) }}">Delete</a>
								</td>
							</tr>
						@endforeach
					</tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" align="right">
                            <a class="btn btn-success" href="{{ route('admin::features.ab_tests.edit') }}">New Ab Test</a>
                        </td>
                    </tr>
				</table>
			</div>
		</div>
	</div>
</div>
@stop
