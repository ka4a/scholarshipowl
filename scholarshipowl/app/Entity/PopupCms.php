<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * PopupCms
 *
 * @ORM\Table(name="popup_cms")
 * @ORM\Entity
 */
class PopupCms
{
    /**
     * @var integer
     *
     * @ORM\Column(name="popup_cms_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $popupCmsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="popup_id", type="integer", nullable=false)
     */
    private $popupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="cms_id", type="integer", nullable=false)
     */
    private $cmsId;

    /**
     * PopupCms constructor.
     *
     * @param int $popupId
     * @param int $cmsId
     */
    public function __construct(int $popupId, int $cmsId)
    {
        $this->popupId = $popupId;
        $this->cmsId = $cmsId;
    }

    /**
     * @return int
     */
    public function getPopupId()
    {
        return $this->popupId;
    }

    /**
     * @param int $popupId
     *
     * @return $this
     */
    public function setPopupId(int $popupId)
    {
        $this->popupId = $popupId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCmsId()
    {
        return $this->cmsId;
    }

    /**
     * @param int $cmsId
     *
     * @return $this
     */
    public function setCmsId(int $cmsId)
    {
        $this->cmsId = $cmsId;
        return $this;
    }
}

