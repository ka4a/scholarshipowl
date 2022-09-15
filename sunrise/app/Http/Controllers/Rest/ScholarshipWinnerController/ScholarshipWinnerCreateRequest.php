<?php namespace App\Http\Controllers\Rest\ScholarshipWinnerController;

use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipFile;
use App\Http\Requests\RestRequest;
use App\Traits\HasEntityManager;
use Illuminate\Http\UploadedFile;

class ScholarshipWinnerCreateRequest extends RestRequest
{
    use HasEntityManager;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->all()['data'];
    }

    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name'          => 'required|max:255',
            'data.attributes.testimonial'   => 'sometimes|max:1024',

            /**
             * Photo relationships fields.
             */
            'data.relationships.image.data' => [
                'required', function($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        return true;
                    }
                    if (is_array($value) && isset($value['id']) && isset($value['type'])) {
                        if (null === $this->em()->find(ScholarshipFile::class, $value['id'])) {
                            return $fail('Scholarship file not exists.');
                        }
                        return true;
                    }

                    return $fail('Please upload image. File should be an image file.');
                },
            ],

            /**
             * Application winner.
             */
            'data.relationships.applicationWinner.data' => [
                'required', function($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        return true;
                    }
                    if (is_array($value) && isset($value['id']) && isset($value['type'])) {
                        if (null === $this->em()->find(ApplicationWinner::class, $value['id'])) {
                            return $fail('Application winner not exists.');
                        }
                        return true;
                    }

                    return $fail('Please upload image. File should be an image file.');
                },
            ],
        ];
    }
}
