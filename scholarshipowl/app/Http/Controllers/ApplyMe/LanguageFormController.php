<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Controllers\ApplyMe;

use App\Entity\ApplyMe\ApplyMeLanguageForm;
use App\Entity\Resource\LanguageFormResource;
use App\Http\Controllers\RestController;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;

class LanguageFormController extends RestController
{

    /**
     * @return mixed
     */
    protected function getRepository() {
        return \EntityManager::getRepository(ApplyMeLanguageForm::class);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBaseIndexQuery(Request $request) {
        return $this->getRepository()->createQueryBuilder('lf');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBaseIndexCountQuery(Request $request) {
        $qb = $this->getBaseIndexQuery($request);
        return $qb->select($qb->expr()->count('lf.id'));
    }

    /**
     * @return mixed
     */
    protected function getResource() {
        return new LanguageFormResource();
    }
}