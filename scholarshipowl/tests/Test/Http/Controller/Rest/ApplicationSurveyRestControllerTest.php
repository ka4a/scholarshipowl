<?php namespace Test\Http\Controller\Rest;

use App\Entity\RequirementSurvey;
use App\Testing\TestCase;

class ApplicationSurveyRestControllerTest extends TestCase
{

    public function testSimpleCrudAction()
    {
        static::$truncate[] = 'application_survey';
        static::$truncate[] = 'requirement_survey';

        $survey = [
            [
                'type' => 'radio',
                'options' =>
                    array (
                        1 => 'red',
                        2 => 'green',
                        3 => 'blue',
                    ),
                'question' => 'Which colour?',
                'description' => 'short description'
            ],
            [
                'type' => 'checkbox',
                'options' =>
                    [
                        1 => 'sun',
                        2 => 'mon',
                        3 => 'tue',
                        4 => 'wed',
                        5 => 'thu',
                        6 => 'fri',
                        7 => 'sat',
                    ],
                'question' => 'Favorite days of week?',
                'description' => 'short description'
            ]
        ];

        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $requirementSurvey = $this->generateRequirementSurvey($scholarship, null, null, null, $survey);

        $answer = $this->prepareAnswersFromSurvey($requirementSurvey);

        $applied = $this->generateAccount('applied@test.com');
        $this->generateApplicationSurvey($requirementSurvey, null, $applied);
        $this->generateApplication($scholarship, $account);

        $this->assertDatabaseMissing('application_survey', [
            'requirement_survey_id' => $requirementSurvey->getId(),
            'account_id' => $account->getAccountId()]
        );

        // ADD/UPDATE
        $resp = $this->post(route('rest::v1.application.survey.store'), [
            'requirementId' => $requirementSurvey->getId(),
            'survey' => $answer,
        ]);

        $applicationId = $resp->getData()->data->id;
        $this->assertDatabaseHas('application_survey', [
                'requirement_survey_id' => $requirementSurvey->getId(),
                'account_id' => $account->getAccountId()
            ]
        );
        $resp = $this->post(route('rest::v1.application.survey.store'), [
            'requirementId' => $requirementSurvey->getId(),
            'survey' => [], // fail it without answers provided
        ]);
        $this->assertTrue($resp->status() === 400);

        // GET
        $resp = $this->get(route('rest::v1.application.survey.show', $applicationId));
        $this->seeJsonSuccessSubset($resp, [
            'requirementId' => $requirementSurvey->getId()
        ]);

        // DELETE
        $resp = $this->delete(route('rest::v1.application.survey.destroy', $applicationId));
        $this->seeJsonSuccessSubset($resp, ['scholarshipId' => 1]);
        $this->assertDatabaseMissing('application_survey', [
                'requirement_survey_id' => $requirementSurvey->getId(),
                'account_id' => $account->getAccountId()]
        );
    }

    /**
     * @param RequirementSurvey $requirementSurvey
     * @return array
     */
    protected function prepareAnswersFromSurvey(RequirementSurvey $requirementSurvey): array
    {
        $surveyWithId = $requirementSurvey->getSurveyWithId();
        $answer = [];
        foreach ($surveyWithId as $question) {
            if ($question['type'] == RequirementSurvey::SURVEY_TYPE_RADIO) {
                $answer[$question['id']][] = array_keys($question['options'])[0];
            }

            if ($question['type'] == RequirementSurvey::SURVEY_TYPE_CHECKBOX) {
                $answer[$question['id']][] = array_keys($question['options'])[0];
                $answer[$question['id']][] = array_keys($question['options'])[1];
            }
        }

        return $answer;
    }
}
