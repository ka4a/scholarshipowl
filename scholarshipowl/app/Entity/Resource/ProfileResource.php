<?php namespace App\Entity\Resource;

use App\Entity\Profile;
use ScholarshipOwl\Data\AbstractResource;

class ProfileResource extends AbstractResource
{
    /**
     * @var Profile
     */
    protected $entity;

    protected $fields = [
        'firstName'                 => null,
        'lastName'                  => null,
        'fullName'                  => null,
        'phone'                     => null,
        'dateOfBirth'               => 'm/d/Y',
        'age'                       => null,
        'gender'                    => null,
        'citizenship'               => DictionaryResource::class,
        'ethnicity'                 => DictionaryResource::class,
        'isSubscribed'              => null,
        'avatar'                    => null,
        'country'                   => DictionaryResource::class,
        'state'                     => StateResource::class,
        'stateName'                 => null,
        'city'                      => null,
        'address'                   => null,
        'address2'                  => null,
        'zip'                       => null,
        'schoolLevel'               => DictionaryResource::class,
        'degree'                    => DictionaryResource::class,
        'degreeType'                => DictionaryResource::class,
        'enrollmentYear'            => null,
        'enrollmentMonth'           => null,
        'gpa'                       => null,
        'careerGoal'                => DictionaryResource::class,
        'graduationYear'            => null,
        'graduationMonth'           => null,
        'highschoolGraduationYear'  => null,
        'highschoolGraduationMonth' => null,
        'studyOnline'               => null,
        'highschool'                => null,
        'highschoolAddress1'        => null,
        'highschoolAddress2'        => null,
        'enrolled'                  => null,
        'universityAddress1'        => null,
        'universityAddress2'        => null,
        'university'                => null,
        'university1'               => null,
        'university2'               => null,
        'university3'               => null,
        'university4'               => null,
        'universities'              => null,
        'distributionChannel'       => null,
        'signupMethod'              => null,
        'militaryAffiliation'       => DictionaryResource::class,
        'profileType'               => null,
        'recurringApplication'      => null,
        'studyCountry1'             => DictionaryResource::class,
        'studyCountry2'             => DictionaryResource::class,
        'studyCountry3'             => DictionaryResource::class,
        'studyCountry4'             => DictionaryResource::class,
        'studyCountry5'             => DictionaryResource::class,
        'agreeCall'                 => null,
        'completeness'              => null,
    ];

    /**
     * ProfileResource constructor.
     *
     * @param Profile|null $profile
     */
    public function __construct(Profile $profile = null)
    {
        $this->entity = $profile;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $result = ['accountId' => $this->entity->getAccount()->getAccountId() ] + parent::toArray();

        if (array_key_exists('gender', $result)) {
            $result['gender'] = !empty($result['gender']) ? strtolower($result['gender']) : null;
        }

        return $result;
    }
}
