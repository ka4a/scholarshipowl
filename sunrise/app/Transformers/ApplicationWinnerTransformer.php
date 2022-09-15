<?php namespace App\Transformers;

use App\Entities\ApplicationFile;
use App\Entities\Scholarship;
use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipWinner;
use Illuminate\Support\Facades\Gate;
use League\Fractal\TransformerAbstract;

class ApplicationWinnerTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['state', 'photo', 'photoSmall', 'affidavit'];

    /**
     * @var array
     */
    protected $availableIncludes = ['application', 'scholarship', 'scholarship_winner'];

    /**
     * @param ApplicationWinner $winner
     * @return array
     */
    public function transform(ApplicationWinner $winner)
    {
        return [
            'id' => $winner->getId(),
            'name' => $winner->getName(),
            'email' => $winner->getEmail(),
            'phone' => $winner->getPhone(),
            'city' => $winner->getCity(),
            'address' => $winner->getAddress(),
            'address2' => $winner->getAddress2(),
            'dateOfBirth' => $winner->getDateOfBirth() ? $winner->getDateOfBirth()->format('Y-m-d') : null,
            'testimonial' => $winner->getTestimonial(),
            'zip' => $winner->getZip(),

            'paypal' => $winner->getPaypal(),
            'bankName' => $winner->getBankName(),
            'nameOfAccount' => $winner->getNameOfAccount(),
            'accountNumber' => $winner->getAccountNumber(),
            'routingNumber' => $winner->getRoutingNumber(),
            'swiftCode' => $winner->getSwiftCode(),

            'meta' => [
                'published' => $winner->getScholarshipWinner() !== null,
                'disqualified' => $winner->isDisqualified(),
                'filled' => $winner->isFilled(),
            ],

            'paused' => $winner->isPaused(),
            'createdAt' => $winner->getCreatedAt()->format('c'),
            'disqualifiedAt' => $winner->getDisqualifiedAt() ? $winner->getDisqualifiedAt()->format('c') : null,
        ];
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeScholarshipWinner(ApplicationWinner $winner)
    {
        if ($scholarshipWinner = $winner->getScholarshipWinner()) {
            return $this->item(
                $winner->getScholarshipWinner(),
                new ScholarshipWinnerTransformer(),
                ScholarshipWinner::getResourceKey()
            );
        }

        return $this->null();
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeScholarship(ApplicationWinner $winner)
    {
        return $this->item(
            $winner->getApplication()->getScholarship(),
            new ScholarshipTransformer(),
            $winner->getApplication()->getScholarship()->getResourceKey()
        );
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeApplication(ApplicationWinner $winner)
    {
        return $this->item(
            $winner->getApplication(),
            new ApplicationTransformerOld(),
            $winner->getApplication()->getResourceKey()
        );
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item
     */
    public function includeState(ApplicationWinner $winner)
    {
        return $this->item($winner->getState(), new StateTransformer(), $winner->getState()->getResourceKey());
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includePhoto(ApplicationWinner $winner)
    {
        if ($photo = $winner->getPhoto()) {
            return $this->item($photo, new ApplicationFileTransformer(), $photo->getResourceKey());
        }
        return $this->null();
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includePhotoSmall(ApplicationWinner $winner)
    {
        if ($photo = $winner->getPhotoSmall()) {
            return $this->item($photo, new ApplicationFileTransformer(), $photo->getResourceKey());
        }
        return $this->null();
    }

    /**
     * @param ApplicationWinner $winner
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeAffidavit(ApplicationWinner $winner)
    {
        return $this->collection(
            $winner->getAffidavit(),
            new ApplicationFileTransformer(),
            ApplicationFile::getResourceKey()
        );
    }
}
