<?php namespace App\Entity\Resource\ApplyMe;

use App\Entity\Profile;
use App\Entity\Resource\DictionaryResource;

class ProfileResource
{

    /**
     * @var Profile
     */
    private $entity;

    public function __construct(Profile $profile)
    {
        $this->entity = $profile;
    }

    public function toArray()
    {
        $citizenship                = new DictionaryResource($this->entity->getCitizenship());
        $ethnicity                  = new DictionaryResource($this->entity->getEthnicity());
        $country                    = new DictionaryResource($this->entity->getCountry());
        $state                      = new DictionaryResource($this->entity->getState());
        $schoolLevel                = new DictionaryResource($this->entity->getSchoolLevel());
        $degree                     = new DictionaryResource($this->entity->getDegree());
        $degreeType                 = new DictionaryResource($this->entity->getDegreeType());
        $careerGoal                 = new DictionaryResource($this->entity->getCareerGoal());
        $militaryAffiliation        = new DictionaryResource($this->entity->getMilitaryAffiliation());

        $dateOfBirth                = $this->entity->getDateOfBirth() ? $this->entity->getDateOfBirth()
                                                    ->format('m/d/Y') : $this->entity->getDateOfBirth();

        return [
            'completeness'          => $this->entity->getCompleteness(),
            'firstName'             => $this->entity->getFirstName(),
            'lastName'              => $this->entity->getLastName(),
            'fullName'              => $this->entity->getFirstName() .' '. $this->entity->getLastName(),
            "phone"                 => $this->entity->getPhone(),
            "dateOfBirth"           => $dateOfBirth,
            "gender"                => $this->entity->getGender(),
            "citizenship"           => $citizenship->isObject() ? $citizenship->toArray() : null,
            "ethnicity"             => $ethnicity->isObject() ? $ethnicity->toArray() : null,
            "isSubscribed"          => $this->entity->getIsSubscribed(),
            "avatar"                => $this->entity->getAvatar(),
            "country"               => $country->isObject() ? $country->toArray() : null,
            "state"                 => $state->isObject() ? $state->toArray() : null,
            "city"                  => $this->entity->getCity(),
            "address"               => $this->entity->getAddress(),
            "zip"                   => $this->entity->getZip(),
            "schoolLevel"           => $schoolLevel->isObject() ? $schoolLevel->toArray() : null,
            "degree"                => $degree->isObject() ? $degree->toArray() : null,
            "degreeType"            => $degreeType->isObject() ? $degreeType->toArray() : null,
            "enrollmentYear"        => $this->entity->getEnrollmentYear(),
            "enrollmentMonth"       => $this->entity->getEnrollmentMonth(),
            "gpa"                   => $this->entity->getGpa(),
            "careerGoal"            => $careerGoal->isObject() ? $careerGoal->toArray() : null,
            "graduationYear"        => $this->entity->getGraduationYear(),
            "graduationMonth"       => $this->entity->getGraduationMonth(),
            "studyOnline"           => $this->entity->getStudyOnline(),
            "highschool"            => $this->entity->getHighschool(),
            "enrolled"              => $this->entity->getEnrolled(),
            "university"            => $this->entity->getUniversity(),
            "university1"           => $this->entity->getUniversity1(),
            "university2"           => $this->entity->getUniversity2(),
            "university3"           => $this->entity->getUniversity3(),
            "university4"           => $this->entity->getUniversity4(),
            "militaryAffiliation"   => $militaryAffiliation->isObject() ? $militaryAffiliation->toArray() : null,
            "profileType"           => $this->entity->getProfileType(),
            "pro"                   => $this->entity->getPro() ? 'true' : 'false',
            "recurring"             => (int)$this->entity->getRecurringApplication(),
        ];
    }
}
