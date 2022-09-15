@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
        @section('box-content')
        {{ Form::open(['method' => 'POST', 'route' => 'admin::scholarships.test', 'files' => true, 'class' => 'form-horizontal']) }}
        {{ Form::hidden('id', $scholarship->getScholarshipId()) }}
            <p><b>Form Action:</b> {{ $scholarship->getFormAction() }}</p>
            <p>{{ Form::input('text', 'form_action', $scholarship->getFormAction(), ['class' => 'form-control']) }}</p>
            <p><b>Form Method:</b> {{ $scholarship->getFormMethod() }}</p>
            <hr />

                <fieldset>
                    @foreach($scholarship->getForms() as $form)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ ucfirst(str_replace("_", " ", $form->getSystemField())) }}
                                ( {{ $form->getFormField() }} )
                            </label>
                            <div class="col-sm-6">
                                @if ($form->getSystemField() === \App\Entity\Form::ACCEPT_CONFIRMATION)
                                    {{ Form::checkbox($form->getFormField(), isset($mapping[$form->getFormId()]) ? $mapping[$form->getFormId()] : null, [
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                    ])}} {{ $form->getValue() }}
                                @elseif (in_array($form->getSystemField(), [\App\Entity\Form::TEXT, \App\Entity\Form::REQUIREMENT_UPLOAD_TEXT]))
                                    @include('admin.scholarships.test.requirement_fill', [
                                        'requirement' => $requirements['texts'][$form->getValue()],
                                    ])
                                @elseif ($form->getSystemField() === \App\Entity\Form::REQUIREMENT_UPLOAD_FILE)
                                    @include('admin.scholarships.test.requirement_fill', [
                                        'requirement' => $requirements['files'][$form->getValue()],
                                    ])
                                @elseif ($form->getSystemField() === \App\Entity\Form::REQUIREMENT_UPLOAD_IMAGE)
                                    @include('admin.scholarships.test.requirement_fill', [
                                        'requirement' => $requirements['images'][$form->getValue()],
                                    ])
                                @elseif ($form->getSystemField() === \App\Entity\Form::INPUT)
                                    @include('admin.scholarships.test.requirement_fill', [
                                        'requirement' => $requirements['inputs'][$form->getValue()],
                                    ])
                                @else
                                    {{ Form::input('text', 'data[' . $form->getFormField() . ']', isset($mapping[$form->getFormId()]) ? $mapping[$form->getFormId()] : null, [
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                    ])}}
                                @endif
                            </div>
                        </div>
                    @endforeach
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
