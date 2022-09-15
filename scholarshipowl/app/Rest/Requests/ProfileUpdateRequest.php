<?php namespace App\Rest\Requests;

use App\Entity\Profile;
use App\Entity\State;
use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Ethnicity;
use App\Entity\MilitaryAffiliation;
use App\Entity\SchoolLevel;

use App\Rest\Requests\RestRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends RestRequest
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->profile()->getAccount() === \Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName'             => 'sometimes|required|string|between:1,127',
            'lastName'              => 'sometimes|required|string|between:1,127',
            'phone'                 => ['sometimes', 'required', 'string', 'regex:/^\+[0-9]{6,20}$|^\(\d{3}\)\s\d{3}\s\-\s\d{4}$/'],
            'dateOfBirth'           => 'sometimes|required|string|date',
            'gender'                => ['sometimes', 'required', Rule::in(array_keys(Profile::genders()))],
            'citizenship'           => Citizenship::exists(),
            'ethnicity'             => Ethnicity::exists(),
            'isSubscribed'          => '',

            // TODO: Implement validation and upload
            // 'avatar'             => 'string|max:1024',

            // we can not validate country because for non US users on register3 it passed as a string
            // but we need to include it into validation rules because API response depends on it
            'country'               => 'sometimes|required', //Country::exists(),
            'state'                 => State::exists(),
            'stateName'             => 'sometimes|required|string|between:1,127',
            'city'                  => 'sometimes|required|string|between:1,255',
            'address'               => 'sometimes|required|string|between:1,511',
            'address2'              => 'string|between:1,511',
            'zip'                   => 'sometimes|required|string|between:1,31',
            'schoolLevel'           => SchoolLevel::exists(),
            'degree'                => Degree::exists(),
            'degreeType'            => DegreeType::exists(),
            'enrollmentYear'        => 'sometimes|required|date_format:Y',
            'enrollmentMonth'       => 'sometimes|required|date_format:n',
            'gpa'                   => 'sometimes|required|string|between:1,3',
            'careerGoal'            => CareerGoal::exists(),
            'graduationYear'        => 'sometimes|required|date_format:Y',
            'graduationMonth'       => 'sometimes|required|date_format:n',
            'highschoolGraduationYear' => 'sometimes|required|date_format:Y',
            'highschoolGraduationMonth' => 'sometimes|required|date_format:n',
            'studyOnline'           => ['sometimes', 'required', Rule::in(['yes', 'no', 'maybe'])],
            'highschool'            => 'sometimes|required|string|between:1,511',
            'highschoolAddress1'    => 'sometimes|required|string|between:1,255',
            'highschoolAddress2'    => 'sometimes|required|string|between:1,255',
            'enrolled'              => 'sometimes|required',

            'universities'          => 'sometimes|required|array',
            'universities.*'        => 'sometimes|required|string|between:1,511',
            'university'            => 'sometimes|required|string|between:1,511',
            'university1'           => 'sometimes|required|string|between:1,511',
            'university2'           => 'sometimes|required|string|between:1,511',
            'university3'           => 'sometimes|required|string|between:1,511',
            'university4'           => 'sometimes|required|string|between:1,511',

            'universityAddress1'    => 'sometimes|required|string|between:1,255',
            'universityAddress2'    => 'sometimes|required|string|between:1,255',
            'distributionChannel'   => ['sometimes', 'required', Rule::in(['web_app', 'ios', 'android'])],
            'signupMethod'          => ['sometimes', 'required', Rule::in(['fb_connect', 'google+', 'manual'])],
            'militaryAffiliation'   => MilitaryAffiliation::exists(),
            'profileType'           => ['sometimes', 'required', Rule::in([
                Profile::PROFILE_TYPE_PARENT,
                Profile::PROFILE_TYPE_STUDENT
            ])],
            'agreeCall'             => '',
            'studyCountries'        => 'sometimes|required|array',
            'studyCountry1'         => Country::exists(),
            'studyCountry2'         => Country::exists(),
            'studyCountry3'         => Country::exists(),
            'studyCountry4'         => Country::exists(),
            'studyCountry5'         => Country::exists(),
            'recurringApplication'  => ['sometimes', 'required', Rule::in([
                Profile::RECURRENT_APPLY_DISABLED,
                Profile::RECURRENT_APPLY_ON_DEADLINE
            ])],

            'password'              => 'string|min:6|same:password_confirmation',
            'countryCode'           => Country::existsByaAbbreviation()
        ];
    }

    public function messages()
    {
        return [
            'password.same' => 'Passwords do not match'
        ];
    }

    /**
     * @return Profile
     */
    public function profile()
    {
        if ($this->profile === null) {
            $this->profile = Profile::repository()->findById($this->route('id'));
        }

        return $this->profile;
    }
}
