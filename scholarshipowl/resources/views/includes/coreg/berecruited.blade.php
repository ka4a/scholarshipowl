<div class="berecruited-tpl">
    <div class="col-sm-6">
        <label>Do you want to play a sport in college?</label>
        <div class="checkboxes clearfix berecruited">
            <div class="pull-left chkbox">
                <label>
                    <input type="radio" value="1" name="coregs[Berecruited][checked]" id="berecruited-yes">
                    <span class="lbl padding-8">
                <span class="lblClr">Yes</span>
                </span>
                </label>
            </div>
            <div class="pull-left chkbox noRight">
                <label>
                    <input type="radio" value="0" name="coregs[Berecruited][checked]" id="berecruited-no" checked>
                    <span class="lbl padding-8">
                    <span class="lblClr">No</span>
                </span>
                </label>
            </div>
            {!! $text !!}
        </div>
        <p class="berecruited-quote">
            "Keep sending prospects our way. You guys are great!"<br>
            - NCAA D1 Head Coach
        </p>
    </div>
    <div class="col-sm-6">
        <img class="berecruited" src="../assets/img/coreg/berecruited_logo.png" alt="Berecruited">
        <label>
            NCSA is the largest recruiting network used by over 35,000 college coaches. To begin your recruiting process
            they will email you a FREE recruiting profile.
        </label>
        <p>
            <a onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=500');return false;"
               href="http://www.ncsasports.org/terms-and-conditions-of-use" target="_blank">Go here for terms and
                conditions.</a></p>
    </div>
</div>

<div class="form-group berecruited-container" style="display: none;">
    <p><strong>Great! We just need a little bit of information.</strong></p>
    <div class="input-group">
        <label class="parent-container">
            <input type="hidden" name="coregs[Berecruited][extra][athlete_or_parent]" value="student"/>
            <input type="checkbox" name="coregs[Berecruited][extra][athlete_or_parent]" value="parent"/>
            <span class="lbl padding-12 mod-checkbox">
                    I am a parent filling out on behalf of my child
                </span>
        </label>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 col-sm-6">
        <div id="parent_first_name" class="form-group">
            <div class="input-group">
                <label for="parent_first_name">Parent First Name</label>
                <input type="text" name="coregs[Berecruited][extra][parent_first_name]"
                       value="{{@$session['parent_first_name']}}"
                       class="form-control" placeholder="Parent First Name" required>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div id="parent_last_name" class="form-group">
            <div class="input-group">
                <label for="parent_last_name">Parent Last Name</label>
                <input type="text" name="coregs[Berecruited][extra][parent_last_name]"
                       value="{{@$session['parent_last_name']}}"
                       class="form-control" placeholder="Parent Last Name" required>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div id="parent_email" class="form-group">
            <div class="input-group">
                <label for="parent_email">Parent Email</label>
                <input type="text" name="coregs[Berecruited][extra][parent_email]" value="{{@$session['parent_email']}}"
                       class="form-control"
                       placeholder="Parent Email" required>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div id="parent_phone_number" class="form-group">
            <div class="input-group">
                <label for="parent_phone_number">Parent Phone Number</label>
                <input name="coregs[Berecruited][extra][parent_phone_number]"
                       value="{{@$session['parent_phone_number']}}" class="form-control"
                       placeholder="Parent Phone Number" type="tel" data-input-id="Phone" id="parent_phone_number_input" required>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div id="hs_graduation_year" class="form-group">
            <div class="input-group">
                <label for="hs_graduation_year">High School Graduation Year</label>
                {!! Form::select("coregs[Berecruited][extra][graduation_year]", array("" => "Please Select...") + array_combine(range(2018, 2024), range(2018, 2024)), @$session["hs_graduation_year"], ["class" => "selectpicker form-control"]) !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div id="city" class="form-group">
            <div class="input-group">
                <label for="sport">Sport</label>
                <select name="coregs[Berecruited][extra][sport_id]" class="selectpicker form-control">
                    <option value="">Select</option>
                    <option value="17706">Baseball</option>
                    <option value="17711">Field Hockey</option>
                    <option value="17633">Football</option>
                    <option value="17638">Men's Basketball</option>
                    <option value="17652">Men's Diving</option>
                    <option value="17659">Men's Golf</option>
                    <option value="17665">Men's Ice Hockey</option>
                    <option value="17707">Men's Lacrosse</option>
                    <option value="17644">Men's Rowing</option>
                    <option value="17683">Men's Soccer</option>
                    <option value="17687">Men's Swimming</option>
                    <option value="17689">Men's Tennis</option>
                    <option value="17695">Men's Volleyball</option>
                    <option value="17701">Men's Water Polo</option>
                    <option value="17634">Softball</option>
                    <option value="17639">Women's Basketball</option>
                    <option value="17730">Women’s Beach Volleyball</option>
                    <option value="17653">Women's Diving</option>
                    <option value="17660">Women's Golf</option>
                    <option value="17666">Women's Ice Hockey</option>
                    <option value="17708">Women's Lacrosse</option>
                    <option value="17645">Women's Rowing</option>
                    <option value="17684">Women's Soccer</option>
                    <option value="17688">Women's Swimming</option>
                    <option value="17690">Women's Tennis</option>
                    <option value="17692">Women's Track</option>
                    <option value="17696">Women's Volleyball</option>
                    <option value="17702">Women's Water Polo</option>
                    <option value="17635">Women's Wrestling</option>
                </select>
            </div>
        </div>
    </div>
</div>
