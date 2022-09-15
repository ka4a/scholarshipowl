<div>
    <h4 style="margin-left: 20px; margin-left: 25px;">Is Optional
        <i class="fa fa-question-circle" data-toggle="tooltip" title="If ticked, then it's not mandatory for a user to complete the requirement in order to apply for the scholarship"></i>
    </h4>

    {{ Form::hidden($requirement_name."[$index][isOptional]", 0) }}
    {{
        Form::checkbox($requirement_name."[$index][isOptional]", 1, isset($requirement) ? $requirement->isOptional() : 0, [
            'class' => 'form-control',
            'style' => 'box-shadow: none; position: relative; top: -33px; width: 20px'
        ])
     }}
</div>


