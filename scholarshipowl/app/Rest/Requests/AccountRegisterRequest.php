<?php namespace App\Rest\Requests;

use App\Entity\Account;
use App\Entity\Citizenship;
use App\Entity\Country;

class AccountRegisterRequest extends RestRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guest();
    }

    /**
     * @return array
     */
    public function rules()
    {
        $domain = \Domain::get()->getId();
        $emailRule = sprintf('required|email|unique:%s,email,NULL,account_id,domain,%s|not_regex:/(.*)application-inbox\.com$/i', Account::class, $domain);

        return [
            'firstName'             => 'required',
            'lastName'              => 'required',
            'email'                 => $emailRule,
            'phone'                 => 'required|regex:/^\+?[0-9]{6,20}$/',

            'countryCode'           => 'size:2|exists:App\Entity\Country,abbreviation',
            'studyCountry'          => 'array|max:5',
            'studyCountry.*'        => 'exists:App\Entity\Country,id',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'firstName.required'            => 'Please enter first name!',
            'lastName.required'             => 'Please enter last name!',
            'email.required'                => 'Please enter email!',
            'email.email'                   => 'Email address is invalid!',
            'email.unique'                  => 'The account already exists',
            'email.not_regex'               => 'Email address is invalid!',
            'phone.required'                => 'Please enter phone!',
            'phone.regex'                   => 'Invalid phone number!',
            'countryCode.required'          => 'Invalid phone number!',
            'countryCode.exists'            => 'Invalid phone number!',
            'studyCountry.array'            => 'Please enter at least one country!',
            'studyCountry.max'              => 'You can only select 5 items!',
            'studyCountry.*.id.exists'      => 'Please enter valid country!',
        ];
    }

    /**
     * @return Country
     */
    public function country()
    {
        return $this->has('countryCode') ?
            Country::findByCountryCode($this->get('countryCode')) : Country::usa();
    }

    /**
     * @return Citizenship
     */
    public function citizenship()
    {
        return $this->has('countryCode') ?
            Citizenship::findByCountryCode($this->get('countryCode')) : null;
    }

    /**
     * @return array
     */
    public function studyCountries()
    {
        $countries = [Country::USA, null, null, null, null];

        if ($studyCountry = $this->get('studyCountry')) {
            array_splice($countries, 0, count($studyCountry), $studyCountry);
        }

        return $countries;
    }

    /**
     * @return mixed
     */
    public function redirect()
    {
        return $this->get('_redirect');
    }

    /**
     * @return bool
     */
    public function isAgreeCall()
    {
        return (bool) $this->get('agreeCall', false);
    }

    /**
     * @return mixed
     */
    public function coregs()
    {
        return $this->get('coregs', false);
    }
}
