<?php namespace App\Http\Controllers\Rest\ApplicationWinnerController;

use App\Http\Requests\RestRequest;
use Illuminate\Http\UploadedFile;

class ApplicationWinnerUpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name'              => 'string|max:255',
            'data.attributes.testimonial'       => 'string|max:1024',
            'data.attributes.dateOfBirth'       => 'date|before:-3 year',
            'data.attributes.email'             => 'email|max:255',
            'data.attributes.phone'             => 'string|max:255',
            'data.attributes.address'           => 'string|max:255',
            'data.attributes.address2'          => 'sometimes|max:255',
            'data.attributes.zip'               => 'regex:/\b\d{5}\b/|max:255',

            'data.attributes.bankName'          => 'string|max:255',
            'data.attributes.nameOfAccount'     => 'string|max:255',
            'data.attributes.accountNumber'     => 'string|max:255',
            'data.attributes.routingNumber'     => 'string|max:255',
            'data.attributes.paypal'            => 'string|max:255',
            'data.attributes.swiftCode'         => 'string|max:255',

            'data.attributes.paused'            => 'boolean',

            /**
             * State relationships fields.
             */
            'data.relationships.state.data' => [
                'sometimes', function($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        return true;
                    }
                    if (is_array($value) && isset($value['id']) && isset($value['type'])) {
                        return true;
                    }

                    return $fail('Please select state.');
                },
            ],

            /**
             * Photo relationships fields.
             */
            'data.relationships.photo.data' => [
                'sometimes', function($attribute, $value, $fail) {
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
                'sometimes', function($attribute, $value, $fail) {
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

        ];
    }
}
