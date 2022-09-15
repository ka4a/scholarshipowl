<div class="form-group">
    <div class="input-group">
        <label class="birddog-sms">
            <span style="position: relative;">
                {!! $checkboxField !!}
                @if( $extraCheckbox !='' )
                <span><i class="style-checkbox-coreg"></i><span style="margin-left:10px">{!! $text !!}</span></span>
                @else
                <span><i class="style-checkbox-coreg" style="top: 5px;"></i><span style="margin-left:10px">{!! $text !!}</span></span>
                @endif
            </span>
            {!! $extraFields !!}
            @if( $extraCheckbox !='' )
                <span style="position: relative">
                    {!! $extraCheckbox !!}
                    <span style="margin-left: 5px"><i class="style-checkbox-coreg"></i><span style="margin-left:10px">
                     By providing your phone number and checking this (optional) box you agree to receive SMS messages from the US Navy and its third-party recruiter partners using automated dialing systems. <a target='_blank' href='https://www.navy.com/privacy-policy'>Terms</a>.
                    </span></span>
                </span>
                {!! $extraFields !!}
            @endif
        </label>
    </div>
</div>
