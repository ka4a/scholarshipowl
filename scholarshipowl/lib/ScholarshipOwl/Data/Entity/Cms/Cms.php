<?php

namespace ScholarshipOwl\Data\Entity\Cms;

use ScholarshipOwl\Data\Entity\AbstractEntity;

class Cms extends AbstractEntity {

    private $cmsId;
    private $url;
    private $page;
    private $title;
    private $keywords;
    private $description;
    private $author;

    public function __construct() {
        $this->cmsId = 0;
        $this->url = "";
        $this->page = "";
        $this->title = "";
        $this->keywords = "";
        $this->description = "";
        $this->author = "";
    }

    public function getCmsId(){
        return $this->cmsId;
    }

    public function setCmsId($cmsId){
        $this->cmsId = $cmsId;
    }


    public function getUrl(){
        return $this->url;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    public function getPage(){
        return $this->page;
    }

    public function setPage($page){
        $this->page = $page;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getKeywords(){
        return $this->keywords;
    }

    public function setKeywords($keywords){
        $this->keywords = $keywords;
    }


    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getAuthor(){
        return $this->author;
    }

    public function setAuthor($author){
        $this->author = $author;
    }

    public function populate($row) {
        foreach ($row as $key => $value) {
            if ($key == "cms_id") {
                $this->setCmsId($value);
            }
            else if ($key == "url") {
                $this->setUrl($value);
            }
            else if ($key == "page") {
                $this->setPage($value);
            }
            else if ($key == "title") {
                $this->setTitle($value);
            }
            else if ($key == "keywords") {
                $this->setKeywords($value);
            }
            else if ($key == "description") {
                $this->setDescription($value);
            }
            else if ($key == "author") {
                $this->setAuthor($value);
            }
        }
    }

    public function toArray() {
        return array(
            "cms_id" => $this->getCmsId(),
            "url" => $this->getUrl(),
            "page" => $this->getPage(),
            "title" => $this->getTitle(),
            "keywords" => $this->getKeywords(),
            "description" => $this->getDescription(),
            "author" => $this->getAuthor()
        );
    }
}