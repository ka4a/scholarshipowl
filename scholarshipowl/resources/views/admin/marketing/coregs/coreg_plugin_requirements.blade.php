<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box-content">
            @php
                $coregRequirementsList = $coreg_plugin->getCoregRequirementsRuleSet();
            @endphp
            @foreach ($coregRequirementsList as $key => $coreg_requirements)
                {{ Form::token() }}
                {{ Form::hidden("redirect_rules_set_id[$key]", $coreg_requirements->getId()) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Type</label>
                        <div class="col-sm-11">
                            {{ Form::select('type', $rule_types, $coreg_requirements->getType(), array("class" => "populate placeholder form-control")) }}
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Rules</label>
                        <div class="col-sm-11">
                            <table class="table table-hover table-striped table-bordered table-heading"
                                   id="RedirectRulesTable">
                                <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Operator</th>
                                    <th>Value</th>
                                    <th>Show rule</th>
                                    <th>Send rule</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($rules = $coreg_requirements->getCoregRequirementsRule())
                                    @foreach ($rules as $redirectRuleId => $redirectRule)
                                        <tr data-redirect_rule-id="{{ $redirectRuleId }}" id="{{ $redirectRuleId }}">
                                            {{ Form::hidden("requirements_rule[$key][$redirectRuleId][id]" , $redirectRuleId) }}
                                            <td>{{ Form::select("requirements_rule[$key][$redirectRuleId][field]", $profile_fields, $redirectRule->getField(), array("class" => "form-control populate placeholder")) }}</td>
                                            <td>{{ Form::select("requirements_rule[$key][$redirectRuleId][operator]", $operators, $redirectRule->getOperator(), array("class" => "form-control populate placeholder")) }}</td>
                                            <td>{{ Form::text( "requirements_rule[$key][$redirectRuleId][value]", $redirectRule->getValue(), array("class" => "form-control rule-value")) }}</td>
                                            <td>
                                                {{ Form::hidden("requirements_rule[$key][$redirectRuleId][active]", 0) }}
                                                {{ Form::checkbox("requirements_rule[$key][$redirectRuleId][active]", "1", $redirectRule->getIsShowRule()) }}

                                            </td>
                                            <td>
                                                {{ Form::hidden("requirements_rule[$key][$redirectRuleId][send]", 0) }}
                                                {{ Form::checkbox("requirements_rule[$key][$redirectRuleId][send]", "1", $redirectRule->getIsSendRule()) }}
                                            </td>
                                            <td><a class="btn btn-danger DeleteRedirectRuleButton" data-redirect-rule-id="{{ $redirectRuleId }}" href="#">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                            <p><a class="btn btn-primary AddCoregRequirements" href="#">Add Rule</a></p>
                        </div>
                    </div>
                </fieldset>
                @endforeach
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <button class="btn btn-primary SaveCoregPlugin" type="submit">Save Coreg Plugin</button>
                        </div>
                    </div>
                </fieldset>

        </div>
    </div>
</div>


<div class="hidden">
    <div id="operators">
        @foreach($operators as $key => $name)
            <option value="{{ $key }}">{{ $name }}</option>
        @endforeach
    </div>

    <div id="profile-fields">
        @foreach($profile_fields as $profile_field)
            <option value="{{ $profile_field }}">{{ $profile_field }}</option>
        @endforeach
    </div>
</div>