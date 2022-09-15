@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
        @section('box-content')
        {{ Form::open(['method' => 'POST', 'route' => 'admin::scholarships.test', 'files' => true, 'class' => 'form-horizontal']) }}
        {{ Form::hidden('id', $scholarship->getScholarshipId()) }}
            <hr />
                @php($profile = $account->getProfile())
                <fieldset>
                    @if($fields)
                    @foreach($fields as $name => $value)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!ucfirst($name)!!}</label>
                            <div class="col-sm-6">
                            @if($name == 'state')
                                {{ Form::select('attributes[state]', $states,
                                $account->getProfile()->getState() ?
                                $account->getProfile()->getState()->getId() : "",
                                ["class" => "populate placeholder select2"]) }}
                            @else
                                {{ Form::input('text', "attributes[{$name}]", $value, ['class' => 'form-control'])}}
                            @endif
                            </div>
                        </div>
                    @endforeach
                    @endif
                </fieldset>
                <fieldset>
                    @if($requirementImage)
                    @foreach($requirementImage as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!$requirement->getTitle()!!}</label>
                            <div class="col-sm-6">
                            {{ Form::file("requirement_images_{$requirement->getId()}", ['class' => 'form-control'])}}
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if($requirementFile)
                    @foreach($requirementFile as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!$requirement->getTitle()!!}</label>
                            <div class="col-sm-6">
                            {{ Form::file("requirement_file_{$requirement->getId()}", ['class' => 'form-control'])}}
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if($requirementInput)
                    @foreach($requirementInput as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!$requirement->getTitle()!!}</label>
                            <div class="col-sm-6">
                            {{ Form::input('text', "requirement_input[{$requirement->getId()}]", '', ['class' => 'form-control'])}}
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if($requirementText)
                    @foreach($requirementText as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!$requirement->getTitle()!!}</label>
                            <div class="col-sm-6">
                            {{ Form::textarea("requirement_text[{$requirement->getId()}]", '', ['class' => 'form-control'])}}
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if($requirementSurvey)
                    @foreach($requirementSurvey as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Survey</label>
                            <div class="col-sm-6">
                            @foreach($requirement->getSurveyWithId() as $key => $survey)
                                {!!$survey['question']!!}
                                @foreach($survey['options'] as $op)
                                    @php($reqId = $requirement->getId())
                                    @php($id = $survey['id'])
                                    @if($survey['type'] == \App\Entity\RequirementSurvey::SURVEY_TYPE_CHECKBOX )
                                        {{ Form::checkbox("requirement_survey[$reqId][$id][]", $op ) }}
                                    @else
                                        {{ Form::radio("requirement_survey[$reqId][$id][]", $op) }}
                                    @endif
                                    {{ $op }}
                                @endforeach
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                    @endif

                    @if($requirementSpElb)
                    @foreach($requirementSpElb as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!!$requirement->getTitle()!!}</label>
                            <div class="col-sm-6">
                            {{ Form::checkbox("requirement_special_eligibility[{$requirement->getId()}]", null, ['class' => 'form-control']) }} {{$requirement->getText()}}
                            </div>
                        </div>
                    @endforeach
                    @endif
                </fieldset>
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="submit" class="btn btn-primary" value="Send Application" />
                        </div>
                    </div>
                </fieldset>
        {{ Form::close() }}
        @overwrite
        @include('admin.common.box', ['boxName' => 'Test Application Send Scholarship'])
    </div>
</div>
@stop
