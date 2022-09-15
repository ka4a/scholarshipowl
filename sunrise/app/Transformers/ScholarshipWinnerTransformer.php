<?php namespace App\Transformers;

use App\Entities\ScholarshipWinner;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ScholarshipWinnerTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'image'
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'scholarship'
    ];

    /**
     * @param ScholarshipWinner $winner
     * @return array
     */
    public function transform(ScholarshipWinner $winner)
    {
        $applicationWinner = $winner->getApplicationWinner();
        $dateOfBirth = $applicationWinner->getDateOfBirth();
        $state = $applicationWinner->getState();
        $age = $applicationWinner->getDateOfBirth() ? Carbon::instance($dateOfBirth)->age : null;

        return [
            'id' => $winner->getId(),
            'name' => $winner->getName(),
            'testimonial' => $winner->getTestimonial(),
            'image_url' => $winner->getImage() ? $winner->getImage()->url() : null,
            'imageUrl' => $winner->getImage() ? $winner->getImage()->url() : null,

            'amount' => (int) $winner->getScholarship()->getAmount(),
            'source' => $applicationWinner->getApplication()->getSource(),
            'state' => $state ? $state->getAbbreviation() : null,
            'zip' => $applicationWinner->getZip(),
            'age' => $age,

            'wonDate' => $applicationWinner->getCreatedAt() ? $applicationWinner->getCreatedAt()->format('c') : null,
            'createdAt' => $winner->getCreatedAt() ? $winner->getCreatedAt()->format('c') : null,
        ];
    }

    /**
     * @param ScholarshipWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeScholarship(ScholarshipWinner $winner)
    {
        return $this->item(
            $winner->getScholarship(),
            new ScholarshipTransformer(),
            $winner->getScholarship()->getResourceKey()
        );
    }

    /**
     * @param ScholarshipWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeImage(ScholarshipWinner $winner)
    {
        if ($image = $winner->getImage()) {
            return $this->item($image, new ScholarshipFileTransformer(), $image->getResourceKey());
        }
        return $this->null();
    }
}
