<div class="formGroupContainer formGroupCheckbox col-xs-12">
    <div class="form-group">
        <div class="input-group">
            <label>
                <input type="checkbox" name="agree_call"
                       value="on" {!! (setting("register.checkbox.call") == "yes")?"checked":"" !!}>
                    <span class="lbl padding-12 mod-checkbox">
                        {!! setting("register.checkbox.call_text") !!}
                    </span>
            </label>
        </div>
    </div>
</div>