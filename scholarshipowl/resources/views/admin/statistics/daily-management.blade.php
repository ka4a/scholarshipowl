@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-xs-12">
        @section('box-content')
            <div class="conversion-graph">
                <div class="row date-picker-container">
                    <div class="col-xs-1">
                        <span>Start</span>
                    </div>
                    <div class="col-xs-4">
                        {{ Form::text('start', null, ["class" => "form-control date_picker"]) }}
                    </div>
                    <div class="col-xs-1">
                        <span>End</span>
                    </div>
                    <div class="col-xs-4">
                        {{ Form::text('end', null, ["class" => "form-control date_picker"]) }}
                    </div>
                    <div class="col-xs-2">
                        {{ Form::button('Show', ['class' => 'btn btn-primary btn-send']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <canvas></canvas>
                    </div>
                </div>
            </div>
        @overwrite
        @include('admin.common.box', ['boxName' => 'Daily Conversion'])
    </div>
</div>

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/daily-management?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search-plus"></i>
					<span>Filter Search</span>
				</div>

				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content " style="display: none;">
				<form method="get" action="/admin/statistics/daily-management" class="form-horizontal">
					<fieldset>

						<div class="form-group">
							<label class="col-sm-3 control-label">Daily Statistic Type</label>
							<div class="col-sm-6">
								{{ Form::select('statistic_daily_type_id[]', $options['statistic_daily_types'], $search['statistic_daily_type_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date from</label>
                            <div class="col-sm-3">
                                {{ Form::text('statistic_daily_date_from', $search['statistic_daily_date_from'], array("class" => "form-control date_picker")) }}
                            </div>
                        </div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Date to</label>
                            <div class="col-sm-3">
                                {{ Form::text('statistic_daily_date_to', $search['statistic_daily_date_to'], array("class" => "form-control date_picker")) }}
                            </div>
                        </div>

					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Search</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-calendar-o"></i>
					<span>Results ({{ $count }})</span>
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
                <div class="table-scroll">
                    <table class="table table-hover table-striped table-bordered table-heading">
                        <thead>
                        <tr>
                            <th width="10%">Date</th>
                            @foreach (reset($statistics) as $date => $statistic)
                                <th>{{ $statistic->getStatisticDailyType() }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($statistics as $date => $statisticData)
                            <tr>
                                <td>{{ $date }}</td>
                                @foreach ($statisticData as $statistic)
                                    <td>{{ $statistic->getValue()?$statistic->getValue():"0" }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
                </div>
			</div>
		</div>
	</div>
</div>


@stop
