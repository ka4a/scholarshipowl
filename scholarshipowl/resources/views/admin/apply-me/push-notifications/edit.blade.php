@extends('admin.base')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            {{ Form::open(['route' => ['admin::applyme.push-notifications.edit', $notification->getId()], 'id' => 'notification-form', 'method' => 'post']) }}
            <table class="table table-hover table-striped table-bordered table-heading">
                <tbody>
                    <tr>
                        <div class="form-group">
                            <td width="10%">
                                <label class="control-label" for="slug">Slug</label>
                            </td>
                            <td>
                                <input class="form-control" type="text" value="{{ $notification->getSlug() }}" name="slug" disabled>
                            </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group">
                            <td>
                                <label class="control-label" for="isActive">IsActive</label>
                            </td>
                            <td>
                                <select name="isActive" class="form-control">
                                    @if ($notification->getIsActive())
                                        <option value="1" selected>true</option>
                                        <option value="0">false</option>
                                    @else
                                        <option value="1">true</option>
                                        <option value="0" selected>false</option>
                                    @endif

                                </select>
                            </td>
                        </div>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            {{ Form::close() }}
        </div>
    </div>
@endsection