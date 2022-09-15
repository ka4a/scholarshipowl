<?php

namespace ScholarshipOwl\Data\Service\Cms;

use ScholarshipOwl\Data\Entity\Cms\Cms;
use ScholarshipOwl\Data\Service\AbstractService;

class CmsService extends AbstractService implements ICmsService {

    const CACHE_TAGS = ['cms'];
    const CACHE_KEY_CMS_BY_URL = 'cms.by-url.%s';

    public function getCms($cmsId){
        $result = null;
        $sql = sprintf("SELECT * FROM %s WHERE cms_id = ?", self::TABLE_CMS);
        $resultSet = $this->query($sql, array($cmsId));
        foreach ($resultSet as $row) {
            $result = new Cms();
            $result->populate((array) $row);
        }
        return $result;
    }

    public function getAllCms(){
        $result = array();
        $sql = sprintf("SELECT * FROM %s", self::TABLE_CMS);

        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $entity = new Cms();
            $entity->populate($row);
            $result[$entity->getCmsId()] = $entity;
        }
        return $result;

    }

    public function addCms(Cms $cms){
        return $this->saveCms($cms, true);
    }
    public function updateCms(Cms $cms){
        return $this->saveCms($cms, false);
    }
    public function deleteCms($cmsId){
        return $this->execute(sprintf("DELETE FROM %s WHERE cms_id = ?", self::TABLE_CMS), array($cmsId));
    }

    private function saveCms(Cms $cms, $insert = true) {
        $result = 0;

        try {
            $this->beginTransaction();

            $cmsId = $cms->getCmsId();
            $data = $cms->toArray();
            unset($data["cms_id"]);


            // Insert Or Update Mission
            if($insert == true) {
                $this->insert(self::TABLE_CMS, $data);
                $cmsId = $this->getLastInsertId();
                $result = $cmsId;
            }
            else {
                $result = $this->update(self::TABLE_CMS, $data, array("cms_id" => $cmsId));
            }
            $this->commit();
        }
        catch (\Exception $exc) {
            $this->rollback();
            throw $exc;
        }
        return $result;
    }
}
