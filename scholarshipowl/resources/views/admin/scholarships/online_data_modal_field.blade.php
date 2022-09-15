<div>
    <h4>System Field</h4>
    <div>
        <select id="MappingFieldsSelect" name="MappingFieldsSelect" class="form-control">
            @foreach ($fields as $systemFieldName => $systemFieldTitle)
                <option
                    value="{{ $systemFieldName }}"
                    @if (!empty($field) && ($field->getSystemField() == $systemFieldName)) {{ "selected" }} @endif
                    @if (array_key_exists($systemFieldName, $static_fields)) {{'data-multi=1'}} @endif
                >
                    {{ $systemFieldTitle }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="FieldInputContainer">
        <div class="InputContainer FieldDefault">
            <h5>Default Value</h5>
            {{ Form::input('text', 'MappingFieldDefaultValue', isset($field) ? $field->getValue() : null, [
                'class' => 'form-control',
            ]) }}
        </div>
        <div class="InputContainer FieldText">
            <h5>Requirement Text</h5>
            @if (empty($requirementsText))
                <p class="alert alert-danger">Please create text requirement (no files allowed).</p>
            @else
                {{ Form::select('MappingFieldDefaultValue', $requirementsText, isset($field) ? $field->getValue() : null, [
                    'class' => 'form-control',
                ]) }}
            @endif
        </div>
        <div class="InputContainer RequirementTextContainer">
            <h5>Requirement Text</h5>
            @if (empty($requirements['texts']))
                <p class="alert alert-danger">Please create text requirement or select another requirement type.</p>
            @else
                {{ Form::select('MappingFieldDefaultValue', $requirements['texts'], isset($field) ? $field->getValue() : null, [
                    'id'    => 'MappingFieldDefaultValue',
                    'class' => 'RequirementText form-control',
                ]) }}
            @endif
        </div>
        <div class="InputContainer RequirementInputContainer">
            <h5>Requirement Input</h5>
            @if (empty($requirements['inputs']))
                <p class="alert alert-danger">Please create input requirement or select another requirement type.</p>
            @else
                {{ Form::select('MappingFieldDefaultValue', $requirements['inputs'], isset($field) ? $field->getValue() : null, [
                    'id'    => 'MappingFieldDefaultValue',
                    'class' => 'RequirementInput form-control',
                ]) }}
            @endif
        </div>
        <div class="InputContainer RequirementFileContainer">
            <h5>Requirement file</h5>
            @if (empty($requirements['files']))
                <p class="alert alert-danger">Please create file requirement or select another requirement type.</p>
            @else
                {{ Form::select('MappingFieldDefaultValue', $requirements['files'], isset($field) ? $field->getValue() : null, [
                    'id'    => 'MappingFieldDefaultValue',
                    'class' => 'RequirementFile form-control',
                ]) }}
            @endif
        </div>
        <div class="InputContainer RequirementImageContainer">
            <h5>Requirement Image</h5>
            @if (empty($requirements['images']))
                <p class="alert alert-danger">Please create image requirement or select another requirement type.</p>
            @else
                {{ Form::select('MappingFieldDefaultValue', $requirements['images'], isset($field) ? $field->getValue() : null, [
                    'id'    => 'MappingFieldDefaultValue',
                    'class' => 'RequirementImage form-control',
                ]) }}
            @endif
        </div>
    </div>

    <div id="MappingEditor" style="display: none;">
        <p><b>Mapping</b></p>

        <p>Form Field</p>
        <div><select id="MappingFormField" size="5" class="form-control"></select></div>
        <br />

        <p>System Field</p>
        <div><select id="MappingSystemField" size="5" multiple="multiple" class="form-control" disabled="disabled"></select></div>

        <div>
            @foreach ($static_fields as $sf_name => $sf_values)
                @foreach ($sf_values as $sf_key => $sf_value)
                    <input type="hidden" name="StaticSystemField" data-field="{{$sf_name}}" data-key="{{$sf_key}}" data-value="{{$sf_value}}" />
                @endforeach
            @endforeach
        </div>


        <div id="AllMappings">
            @if (!empty($field))
                @if (is_array($field->getMapping()))
                    @foreach ($field->getMapping() as $k => $vals)
                        @foreach ($vals as $v)
                            <input type="hidden" name="mapping[]" value="{{$v}}" data-field="{{$field->getSystemField()}}" data-value="{{$k}}" />
                        @endforeach
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</div>

