<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\ApplicationFile;
use App\Entities\State;
use App\Http\Requests\RestRequest;
use Illuminate\Http\UploadedFile;

class ApplicationRelatedWinnerUpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'data.attributes.dateOfBirth.before' => 'Please enter valid :attribute.',
            'data.attributes.zip.regex' => 'Please enter valid :attribute.',
            'data.attributes.paypal.required_without_all' => 'Please enter paypal account.',
            'data.attributes.swiftCode.required_without_all' => 'Please enter :attribute.',
            'data.attributes.bankName.required_with' => 'Please enter :attribute.',
            'data.attributes.bankName.required_without_all' => 'Please enter :attribute.',
            'data.attributes.nameOfAccount.required_with' => 'Please enter :attribute.',
            'data.attributes.nameOfAccount.required_without_all' => 'Please enter :attribute.',
            'data.attributes.accountNumber.required_with' => 'Please enter :attribute.',
            'data.attributes.accountNumber.required_without_all' => 'Please enter :attribute.',
            'data.attributes.routingNumber.required_with' => 'Please enter :attribute.',
            'data.attributes.routingNumber.required_without_all' => 'Please enter :attribute.',
        ];
    }

    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name'              => 'required|string|max:255',
            'data.attributes.testimonial'       => 'sometimes|string|max:1024',
            'data.attributes.dateOfBirth'       => 'required|date|before:-3 year',
            'data.attributes.email'             => 'required|email|max:255',
            'data.attributes.phone'             => 'required|string|max:255',
            'data.attributes.city'              => 'required|string|max:255',
            'data.attributes.address'           => 'required|string|max:255',
            'data.attributes.address2'          => 'sometimes|max:255',
            'data.attributes.zip'               => 'required|regex:/\b\d{5}\b/|max:255',


            /**
             * State relationship.
             */
            'data.relationships.state.data.type'      => [
                'required', 'in:'.State::getResourceKey(),
            ],
            'data.relationships.state.data.id'        => [
                'required', 'numeric', 'exists:'.State::class.',id'
            ],

            /**
             * Photo relationships fields.
             */
            'data.relationships.photo.data' => [
                'required', function($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        return true;
                    }
                    if (is_array($value) && isset($value['id']) && isset($value['type'])) {
                        return true;
                    }

                    return $fail('Please upload photo. File should be an image file.');
                },
            ],

            /**
             * Affidavit file validation.
             */
            'data.relationships.affidavit.data' => [
                'required', function($attribute, $value, $fail) {
                    if (is_array($value)) {
                        foreach ($value as $file) {
                            if (!$file instanceof UploadedFile) {
                                return false;
                            }
                            if (!is_array($file) || !isset($file['id']) || !isset($value['type'])) {
                                return false;
                            }
                        }
                        return true;
                    }

                    return $fail('Please signed affidavit file.');
                },
            ],

            'data.attributes.bankName'          => [
                'max:255',
                'required_with:'.implode(',', [
                    'data.attributes.nameOfAccount',
                    'data.attributes.accountNumber',
                    'data.attributes.routingNumber',
                ]),
                'required_without_all:'.implode(',', [
                    'data.attributes.paypal',
                    'data.attributes.swiftCode',
                ]),
            ],
            'data.attributes.nameOfAccount'          => [
                'max:255',
                'required_with:'.implode(',', [
                    'data.attributes.bankName',
                    'data.attributes.accountNumber',
                    'data.attributes.routingNumber',
                ]),
                'required_without_all:'.implode(',', [
                    'data.attributes.paypal',
                    'data.attributes.swiftCode',
                ]),
            ],
            'data.attributes.accountNumber'          => [
                'max:255',
                'required_with:'.implode(',', [
                    'data.attributes.bankName',
                    'data.attributes.nameOfAccount',
                    'data.attributes.routingNumber',
                ]),
                'required_without_all:'.implode(',', [
                    'data.attributes.paypal',
                    'data.attributes.swiftCode',
                ]),
            ],
            'data.attributes.routingNumber'          => [
                'max:255',
                'required_with:'.implode(',', [
                    'data.attributes.bankName',
                    'data.attributes.nameOfAccount',
                    'data.attributes.accountNumber',
                ]),
                'required_without_all:'.implode(',', [
                    'data.attributes.paypal',
                    'data.attributes.swiftCode',
                ]),
            ],

            'data.attributes.paypal'            => [
                'max:255',
                'required_without_all:'.implode(',', [
                    'data.attributes.swiftCode',
                    'data.attributes.bankName',
                    'data.attributes.nameOfAccount',
                    'data.attributes.accountNumber',
                    'data.attributes.routingNumber',
                ])
            ],

            'data.attributes.swiftCode'            => [
                'max:255',
                'required_without_all:'.implode(',', [
                    'data.attributes.paypal',
                    'data.attributes.bankName',
                    'data.attributes.nameOfAccount',
                    'data.attributes.accountNumber',
                    'data.attributes.routingNumber',
                ])
            ],
        ];
    }
}
