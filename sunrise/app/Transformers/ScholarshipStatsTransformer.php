<?php namespace App\Transformers;

use App\Entities\Application;
use App\Entities\ApplicationStatus;
use App\Entities\ApplicationWinner;
use App\Entities\Scholarship;
use App\Entities\ScholarshipWinner;
use App\Traits\HasEntityManager;
use League\Fractal\TransformerAbstract;

class ScholarshipStatsTransformer extends TransformerAbstract
{
    use HasEntityManager;

    /**
     * @param Scholarship $scholarship
     * @return array
     */
    public function transform(Scholarship $scholarship)
    {
        $byStatus = $this->em()->getRepository(Application::class)
            ->createQueryBuilder('a')
            ->select(['IDENTITY(a.status)', 'COUNT(1)'])
            ->where('a.scholarship = :scholarship')
            ->groupBy('a.status')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getArrayResult();

        $total = 0;
        $new = 0;
        $rejected = 0;
        $accepted = 0;
        $unreviewed = 0;

        foreach ($byStatus as $status) {
            if ($status[1] === ApplicationStatus::RECEIVED) {
                $new += (int) $status[2];
                $total += (int) $status[2];
            }
            if ($status[1] === ApplicationStatus::REJECTED) {
                $rejected = (int) $status[2];
                $total += (int) $status[2];
            }
            if ($status[1] === ApplicationStatus::ACCEPTED) {
                $accepted = (int) $status[2];
                $total += (int) $status[2];
            }
            if ($status[1] === ApplicationStatus::REVIEW) {
                $unreviewed += (int) $status[2];
                $total += (int) $status[2];
            }
        }

        $winnerTotal = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->select('COUNT(1)')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's')
            ->where('s.id = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getSingleScalarResult();

        $disqualified = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->select('COUNT(1)')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's')
            ->where('s.id = :scholarship AND aw.disqualifiedAt IS NOT NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getSingleScalarResult();

        $awarded = $this->em()->getRepository(ScholarshipWinner::class)
            ->createQueryBuilder('sw')
            ->select('COUNT(1)')
            ->where('sw.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getSingleScalarResult();

        $sources = $this->em()->getRepository(Application::class)
            ->createQueryBuilder('a')
            ->select(['a.source', 'COUNT(a) as cnt'])
            ->join('a.scholarship', 's', 'WITH', 's.id = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->groupBy('a.source')
            ->getQuery()
            ->getArrayResult();


        $barnSource = 0;
        $sowlSource = 0;
        $noneSource = 0;
        foreach ($sources as $source) {
            if (isset($source['source']) && $source['source'] === 'barn' && isset($source['cnt'])) {
                $barnSource = intval($source['cnt']);
            }
            if (isset($source['source']) && $source['source'] === 'sowl' && isset($source['cnt'])) {
                $sowlSource = intval($source['cnt']);
            }
            if (isset($source['source']) && $source['source'] === 'none' && isset($source['cnt'])) {
                $noneSource = intval($source['cnt']);
            }
        }

        return [
            'id' => $scholarship->getId(),
            'total' => $total,
            'new' => $new,
            'rejected' => $rejected,
            'accepted' => $accepted,
            'unreviewed' => $unreviewed,
            'winners' => [
                'total' => $winnerTotal,
                'awarded' => $awarded,
                'disqualified' => $disqualified,
            ],
            'sources' => [
                'sowl' => $sowlSource,
                'barn' => $barnSource,
                'none' => $noneSource
            ]
        ];
    }
}
