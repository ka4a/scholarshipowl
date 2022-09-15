@extends("admin/base")
@section("content")

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="box-name">
                                <i class="fa fa-university"></i>
                                <span>Results ({{ $count }})</span>
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

                        <div class="box-content">
                            <table class="table table-bordered table-striped table-hover table-heading">
                                <thead>
                                <tr>
                                    <th>Matches</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Deadline</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($scholarships as $scholarship)
                                    <tr>
                                        <td>{{ $scholarship["num_matches"] }}</td>
                                        <td>{{ $scholarship[0]->getTitle() }}</td>
                                        <td>
                                            <a href="{{ $scholarship[0]->getUrl() }}">{{ $scholarship[0]->getUrl() }}</a>
                                        </td>
                                        <td>{{ $scholarship[0]->getDeadline() }}</td>
                                        <td>{{ $scholarship[0]->getAmount() }}</td>
                                        <td><a class="btn btn-success"
                                               href="/admin/scholarships/super-college-match?id={{ $scholarship[0]->getId() }}">View</a>
                                        </td>
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
    </div>


@stop
