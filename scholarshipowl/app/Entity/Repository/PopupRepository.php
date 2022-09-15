<?php
namespace App\Entity\Repository;

use App\Entity\Cms;
use App\Entity\Popup;
use App\Entity\PopupCms;
use Carbon\Carbon;
use Doctrine\ORM\Query\Expr\Join;


class PopupRepository extends EntityRepository
{
    /**
     * Get all popup by page url
     * @param string $pageUrl
     *
     * @return mixed
     */
    public function getAllByUrl($pageUrl)
    {
        $qb = $this->createQueryBuilder('p');
        $popups = $qb
            ->leftJoin(
                PopupCms::class, 'pc', Join::WITH, 'pc.popupId = p.popupId'
            )
            ->leftJoin(Cms::class, 'c', Join::WITH, 'pc.cmsId = c.cmsId')
            ->where($qb->expr()->eq('c.url', ':url'))
            ->andWhere($qb->expr()->gte('p.endDate', ':now'))
            ->andWhere($qb->expr()->lte('p.startDate', ':now'))
            ->andWhere("p.popupDisplay != '0'")
            ->addOrderBy('p.priority', 'desc')
            ->addOrderBy('p.popupId', 'desc')
            ->setParameters(
                [
                    'url' => $pageUrl,
                    'now' => Carbon::now()
                ]
            )->getQuery()->getResult();

        return $popups;
    }
}
