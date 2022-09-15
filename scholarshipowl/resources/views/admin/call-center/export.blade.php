@extends("admin.base")
@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-content">
                    <form method="get" action="/admin/export/call-center" class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Enter Account IDs or Emails</label>

                                <div class="col-sm-6">
                                    {{ Form::textarea('accounts', null, array("class" => "form-control")) }}
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button class="btn btn-primary" type="submit">Export</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
