<?php

namespace ScholarshipOwl\Data\Service\Cms;

use ScholarshipOwl\Data\Entity\Cms\Cms;


interface ICmsService {

    public function getCms($cmsId);
    public function getAllCms();

    public function addCms(Cms $cms);
    public function updateCms(Cms $cms);
    public function deleteCms($cmsId);


}