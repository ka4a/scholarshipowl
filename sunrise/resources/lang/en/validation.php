<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'Please upload :attribute.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'Please enter :attribute.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    'min_words'            => 'The :attribute must be at least :value words.',
    'max_words'            => 'The :attribute must be maximum :value words.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'data.attributes.scholarshipId' => 'scholarship',
        'data.attributes.name' => 'name',
        'data.attributes.email' => 'email',
        'data.attributes.phone' => 'phone',
        'data.attributes.city' => 'city',
        'data.attributes.address' => 'address',
        'data.attributes.address2' => 'address',
        'data.attributes.zip' => 'zip code',
        'data.attributes.bankName' => 'bank name',
        'data.attributes.nameOfAccount' => 'name of account',
        'data.attributes.accountNumber' => 'account number',
        'data.attributes.routingNumber' => 'routing number',
        'data.attributes.paypal' => 'paypal',
        'data.attributes.swiftCode' => 'swift code',
        'data.attributes.dateOfBirth' => 'date of birth',
        'data.attributes.testimonial' => 'testimonial',

        'data.attributes.scholarshipUrl' => 'Scholarship URL',
        'data.attributes.scholarshipPPUrl' => 'Scholarship privacy policy URL',
        'data.attributes.scholarshipTOSUrl' => 'Scholarship terms of URL',

        'data.attributes.title' => 'title',
        'data.attributes.description' => 'description',
        'data.attributes.timezone' => 'timezone',
        'data.attributes.start' => 'start',
        'data.attributes.deadline' => 'deadline',
        'data.attributes.amount' => 'amount',
        'data.attributes.awards' => 'awards',
        'data.attributes.recurringValue' => 'recurring value',
        'data.attributes.recurringType' => 'recurring type',

        'data.attributes.domain' => 'domain',

        'data.attributes.intro' => 'intro',
        'data.attributes.companyName' => 'company name',

        'data.relationships.photo.data' => 'photo',
        'data.relationships.image.data' => 'image',
        'data.relationships.affidavit.data' => 'affidavit',
    ],

    'scholarship_not_published' => 'Sorry but scholarship not published.',

    /*
    |--------------------------------------------------------------------------
    | Eligibility rules messages
    |--------------------------------------------------------------------------
    */
    'eligibility_equals' => 'The :attribute must be equal :eligibilityValue.',
    'eligibility_not' => 'The :attribute must be not equal :eligibilityValue.',
    'eligibility_lt' => 'The :attribute must be less than :eligibilityValue.',
    'eligibility_lte' => 'The :attribute must be less or equal :eligibilityValue.',
    'eligibility_gt' => 'The :attribute must be bigger than :eligibilityValue.',
    'eligibility_gte' => 'The :attribute must be bigger or equal :eligibilityValue.',
    'eligibility_between' => 'The :attribute must be between :min and :max.',
    'eligibility_in' => 'The :attribute must be one of :eligibilityValue.',
    'eligibility_not_in' => 'The :attribute must be not one of :eligibilityValue.',

];
