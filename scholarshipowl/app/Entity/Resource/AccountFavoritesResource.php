<?php namespace App\Entity\Resource;

use App\Entity\AccountsFavoriteScholarships;
use ScholarshipOwl\Data\AbstractResource;

class AccountFavoritesResource extends AbstractResource
{
    /**
     * @var AccountsFavoriteScholarships
     */
    protected $entity;

    protected $fields =
        [
            'scholarshipId'      => ScholarshipResource::class,
        ];

    /**
     * PopupResource constructor.
     *
     * @param AccountsFavoriteScholarships|null $favorite
     */
    public function __construct(AccountsFavoriteScholarships $favorite = null)
    {
        $this->entity = $favorite;
    }
}
