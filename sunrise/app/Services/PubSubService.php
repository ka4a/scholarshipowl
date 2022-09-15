<?php namespace App\Services;

use App\Entities\Application;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Entities\ScholarshipRequirement;
use App\Entities\ScholarshipWinner;
use App\Transformers\ApplicationTransformerOld;
use App\Transformers\FieldTransformer;
use App\Transformers\RequirementTransformer;
use App\Transformers\ScholarshipFieldTransformer;
use App\Transformers\ScholarshipRequirementTransformer;
use App\Transformers\ScholarshipTransformer;
use App\Transformers\ScholarshipWinnerTransformer;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Facades\App;

class PubSubService
{
    const TOPIC_SCHOLARSHIP = 'sunrise.scholarships';
    const TOPIC_APPLICATION = 'sunrise.applications';

    const MESSAGE_SCHOLARSHIP_PUBLISHED             = 'scholarship.published';
    const MESSAGE_SCHOLARSHIP_STATUS_CHANGED        = 'scholarship.status_changed';
    const MESSAGE_SCHOLARSHIP_DEADLINE              = 'scholarship.deadline';

    const MESSAGE_APPLICATION_APPLIED               = 'application.applied';
    const MESSAGE_APPLICATION_STATUS_CHANGED        = 'application.status_changed';

    const MESSAGE_APPLICATION_WINNER                = 'application.winner';
    const MESSAGE_APPLICATION_WINNER_FILLED         = 'application.winner_filled';
    const MESSAGE_APPLICATION_WINNER_PUBLISHED      = 'application.winner_published';
    const MESSAGE_APPLICATION_WINNER_DISQUALIFIED   = 'application.winner_disqualified';

    /**
     * @var PubSubClient
     */
    protected $pubsub;

    /**
     * PubSubService constructor.
     * @param PubSubClient $pubsub
     */
    public function __construct(PubSubClient $pubsub)
    {
        $this->pubsub = $pubsub;
    }

    /**
     * Setup all topics for specific environment.
     */
    public function setup()
    {
        $topics = [
            static::TOPIC_SCHOLARSHIP,
            static::TOPIC_APPLICATION,
        ];

        foreach ($topics as $name) {
            $topic = $this->getPubSubTopic($name);
            if (!$topic->exists()) {
                $topic->create();
            }
        }
    }

    /**
     * @param Scholarship $scholarship
     * @return array
     * @throws \Exception
     */
    public function pubScholarshipPublished(Scholarship $scholarship)
    {
        return $this->publishMessage(
            static::TOPIC_SCHOLARSHIP,
            static::MESSAGE_SCHOLARSHIP_PUBLISHED,
            $this->prepareScholarshipData($scholarship), [
                'id' => $scholarship->getId(),
            ]
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return array
     * @throws \Exception
     */
    public function pubScholarshipStatusChanged(Scholarship $scholarship)
    {
        return $this->publishMessage(
            static::TOPIC_SCHOLARSHIP,
            static::MESSAGE_SCHOLARSHIP_STATUS_CHANGED,
            $this->prepareScholarshipData($scholarship), [
                'id' => $scholarship->getId(),
            ]
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return array
     * @throws \Exception
     */
    public function pubScholarshipDeadline(Scholarship $scholarship)
    {
        return $this->publishMessage(
            static::TOPIC_SCHOLARSHIP,
            static::MESSAGE_SCHOLARSHIP_DEADLINE,
            $this->prepareScholarshipData($scholarship), [
                'id' => $scholarship->getId(),
            ]
        );
    }

    /**
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function pubApplicationApplied(Application $application)
    {
        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_APPLIED,
            $this->prepareApplicationData($application), [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function pubApplicationStatusChanged(Application $application)
    {
        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_STATUS_CHANGED,
            $this->prepareApplicationData($application), [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function pubApplicationWinner(Application $application)
    {
        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_WINNER,
            $this->prepareApplicationData($application) + [
                'url_winner_information' => route('winner-information', $application->getId())
            ], [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function pubApplicationWinnerFilled(Application $application)
    {
        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_WINNER_FILLED,
            $this->prepareApplicationData($application) + [
                'url_winner_information' => route('winner-information', $application->getId())
            ], [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param Application $application
     * @return array
     * @throws \Exception
     */
    public function pubApplicationWinnerDisqualified(Application $application)
    {
        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_WINNER_DISQUALIFIED,
            $this->prepareApplicationData($application) + [
                'url_winner_information' => route('winner-information', $application->getId())
            ], [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param ScholarshipWinner $winner
     * @return array
     * @throws \Exception
     */
    public function pubApplicationWinnerPublished(ScholarshipWinner $winner)
    {
        $application = $winner->getApplicationWinner()->getApplication();

        return $this->publishMessage(
            static::TOPIC_APPLICATION,
            static::MESSAGE_APPLICATION_WINNER_PUBLISHED,
            $this->prepareApplicationData($application) + [
                'url_winner_information' => route('winner-information', $application->getId()),
                'winner' => (new ScholarshipWinnerTransformer())->transform($winner)
            ], [
                'id' => $application->getId(),
                'scholarship_id' => $application->getScholarship()->getId(),
                'source' => $application->getSource(),
            ]
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return array
     * @throws \Exception
     */
    protected function prepareScholarshipData(Scholarship $scholarship)
    {
        $transformer = new ScholarshipTransformer();
        $fieldTransformer = new FieldTransformer();
        $requirementTransformer = new RequirementTransformer();
        $scholarshipRequirementTransformer = new ScholarshipRequirementTransformer();
        $scholarshipFieldTransformer = new ScholarshipFieldTransformer();

        $data = $transformer->transform($scholarship) + [
            'template' => $scholarship->getTemplate()->getId(),
            'url' => $scholarship->getPublicUrl(),
            'url_privacy_policy' => $scholarship->getPublicPPUrl(),
            'url_terms_of_use' => $scholarship->getPublicTOSUrl(),
        ];

        $data['fields'] = array_map(
            function(ScholarshipField $field) use ($scholarshipFieldTransformer, $fieldTransformer) {
                return $scholarshipFieldTransformer->transform($field) + [
                    'field' => $fieldTransformer->transform($field->getField()),
                ];
            },
            $scholarship->getFields()->toArray()
        );

        $data['requirements'] = array_map(
            function(ScholarshipRequirement $requirement) use (
                $scholarshipRequirementTransformer,
                $requirementTransformer
            ) {
                return $scholarshipRequirementTransformer->transform($requirement) + [
                    'requirement' => $requirementTransformer->transform($requirement->getRequirement())
                ];
            },
            $scholarship->getRequirements()->toArray()
        );

        return $data;
    }

    /**
     * @param Application $application
     * @return array
     */
    protected function prepareApplicationData(Application $application)
    {
        $transformer = new ApplicationTransformerOld();
        $data = $transformer->transform($application);
        $data['status'] = $application->getStatus()->getId();
        return $data;
    }

    /**
     * @param string $topic
     * @param string $action
     * @param array $data
     * @param array $attributes
     * @return array
     * @throws \Exception
     */
    protected function publishMessage($topic, $action, array $data, array $attributes)
    {
        return $this->getPubSubTopic($topic)->publish([
            'data' => $this->prepareData($data),
            'attributes' => $this->prepareAttributes($action, $attributes)
        ]);
    }

    /**
     * @param string $name
     * @return \Google\Cloud\PubSub\Topic
     */
    protected function getPubSubTopic($name)
    {
        return $this->pubsub->topic($this->getTopicName($name));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getTopicName($name)
    {
        return App::environment().'.'.$name;
    }

    /**
     * @param $data
     * @return string
     */
    protected function prepareData($data)
    {
        return json_encode($data);
    }

    /**
     * @param string $message
     * @param array $attributes
     * @return mixed
     * @throws \Exception
     */
    protected function prepareAttributes($message, array $attributes = [])
    {
        $attributes['timestamp'] = (new \DateTime())->getTimestamp();
        $attributes['event'] = $message;

        return array_map(
            function($value) {
                return addslashes($value);
            },
            array_filter($attributes, function($value) {
                return !is_array($value);
            })
        );
    }
}
