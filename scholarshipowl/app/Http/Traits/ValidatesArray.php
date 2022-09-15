<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Traits;

use Illuminate\Validation\ValidationException;

trait ValidatesArray
{
    /**
     * @param array $data
     * @param array  $rules
     * @throws ValidationException
     */
    public function validate(array $data, array $rules)
    {
        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

}