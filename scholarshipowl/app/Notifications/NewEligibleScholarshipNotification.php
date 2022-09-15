<?php namespace App\Notifications;

use App\Entity\Account;
use App\Entity\Scholarship;
use App\Entity\NotificationType;

class NewEligibleScholarshipNotification extends AbstractNotification
{
    /**
     * @var int
     */
    public $scholarshipId;

    /**
     * @var Scholarship
     */
    protected $scholarship;

    /**
     * Create a new notification instance.
     *
     * @param $scholarshipId
     */
    public function __construct($scholarshipId)
    {
        parent::__construct();

        $this->scholarshipId = $scholarshipId;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        if ($this->scholarship === null) {
            $this->scholarship = \EntityManager::find(Scholarship::class, $this->scholarshipId);
        }

        return $this->scholarship;
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return NotificationType::NOTIFICATION_NEW_ELIGIBLE_SCHOLARSHIP;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function mapTags(string $content, Account $account) : string
    {
        return map_tags_provider($content, [
            ['account', $account],
            ['scholarship', $this->getScholarship()]
        ]);
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return parent::getData() + [
            'scholarshipId' => $this->scholarshipId,
        ];
    }
}
