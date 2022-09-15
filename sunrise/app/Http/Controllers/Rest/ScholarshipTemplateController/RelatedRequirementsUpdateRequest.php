<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\Requirement;
use App\Http\Requests\RestRequest;
use App\Rules\Data;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory;

class RelatedRequirementsUpdateRequest extends RestRequest
{
    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function entityRules()
    {
        $rules = [];

        /** @var Factory $factory */
        $factory = app(Factory::class);
        $validated = $factory->make($this->all(), [
            'data.array',
            'data.*.relationships.requirement'  => new Data(Requirement::class),
        ])->validate();

        if (isset($validated['data']) && is_array($validated['data'])) {
            foreach ($validated['data'] as $i => $item) {
                $requirement = Requirement::find(Arr::get($item, 'relationships.requirement.data.id'));
                $rules["data.$i.relationships.requirement"] = 'required';
                $rules["data.$i.attributes.title"] = 'required|string|max:255';
                $rules["data.$i.attributes.description"] = 'required|string';

                foreach ($this->buildConfigRules($requirement->getType()) as $key => $rule) {
                    $rules["data.$i.attributes.config.$key"] = $rule;
                }
            }
        }

        return $rules;
    }

    /**
     * @param $type
     * @return array
     */
    protected function buildConfigRules($type)
    {
        $rules = [];

        switch ($type) {
            case Requirement::TYPE_TEXT:
                $rules[Requirement::TYPE_TEXT_KEY_MIN_WORDS]       = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_TEXT_KEY_MAX_WORDS]       = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_TEXT_KEY_MIN_CHARS]       = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_TEXT_KEY_MAX_CHARS]       = 'sometimes|required|numeric';
                break;
            case Requirement::TYPE_INPUT:
                $rules[Requirement::TYPE_TEXT_KEY_MIN_CHARS]       = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_TEXT_KEY_MAX_CHARS]       = 'sometimes|required|numeric';
                break;
            case Requirement::TYPE_LINK:
                $rules[Requirement::TYPE_TEXT_KEY_MIN_CHARS]       = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_TEXT_KEY_MAX_CHARS]       = 'sometimes|required|numeric';
                break;
            case Requirement::TYPE_FILE:
                $rules[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS] = 'sometimes|required|string';
                $rules[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE]   = 'sometimes|required|numeric';
                break;
            case Requirement::TYPE_IMAGE:
                $rules[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS] = 'sometimes|required|string';
                $rules[Requirement::TYPE_FILE_KEY_MAX_FILE_SIZE]   = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_IMAGE_KEY_MIN_WIDTH]      = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_IMAGE_KEY_MAX_WIDTH]      = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_IMAGE_KEY_MIN_HEIGHT]     = 'sometimes|required|numeric';
                $rules[Requirement::TYPE_IMAGE_KEY_MAX_HEIGHT]     = 'sometimes|required|numeric';
                break;
            case Requirement::TYPE_VIDEO:
                $rules[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS] = 'sometimes|required|string';
                $rules[Requirement::TYPE_FILE_KEY_FILE_EXTENSIONS] = 'sometimes|required|numeric';
                break;
        }

        return $rules;
    }
}
