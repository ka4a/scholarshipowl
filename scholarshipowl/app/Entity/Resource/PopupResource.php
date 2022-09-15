<?php namespace App\Entity\Resource;

use App\Entity\Popup;
use ScholarshipOwl\Data\AbstractResource;

class PopupResource extends AbstractResource
{
    /**
     * @var Popup
     */
    protected $entity;

    protected $fields =
        [
            'popupId'           => null,
            'popupDisplay'      => null,
            'popupTitle'        => null,
            'popupText'         => null,
            'popupType '        => null,
            'popupTargetId'     => null,
            'popupDelay'        => null,
            'popupDisplayTimes' => null,
            'triggerUpgrade'    => null
        ];

    /**
     * PopupResource constructor.
     *
     * @param Popup|null $popup
     */
    public function __construct(Popup $popup = null)
    {
        $this->entity = $popup;
    }
}
