<?php namespace App\Http\Controllers\Rest\UserController;

use App\Entities\User;
use App\Http\Requests\RestRequest;
use Illuminate\Http\UploadedFile;

class UserUpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name' => 'sometimes|max:255',
            'data.attributes.email' => 'sometimes|email|max:255|unique:'.User::class.',email',

            /**
             * Photo relationships fields.
             */
            'data.attributes.picture' => [
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

        ];
    }
}
