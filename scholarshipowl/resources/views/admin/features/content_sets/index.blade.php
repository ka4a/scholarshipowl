@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-html"></i>
                    <span>Content sets</span>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Homepage header</th>
                            <th>Register header</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($contentSets as $contentSet)
                        <tr>
                            <td>{{ $contentSet->getId() }}</td>
                            <td>{{ $contentSet->getName() }}</td>
                            <td>{{ strip_tags($contentSet->getHomepageHeader()) }}</td>
                            <td>{{ strip_tags($contentSet->getRegisterHeader()) }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route('admin::features.content_sets.edit', $contentSet->getId()) }}">Edit</a>
                                <a class="btn btn-info clone-feature" data-href="{{ route('admin::features.content_sets.clone', $contentSet->getId()) }}">Clone</a>
                                <a class="btn btn-danger"
                                   href="{{ route('admin::features.content_sets.delete', $contentSet->getId()) }}"
                                   data-confirm-message="{{ sprintf("Are you sure want to remove '%s' content set?", $contentSet->getName()) }}"
                                >Remove</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" align="right">
                            <a class="btn btn-success" href="{{ route('admin::features.content_sets.edit') }}">New content set</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
