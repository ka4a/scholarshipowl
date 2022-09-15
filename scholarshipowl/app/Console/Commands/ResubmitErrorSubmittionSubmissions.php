<?php

namespace App\Console\Commands;

use App\Entity\Marketing\Coreg\CoregResubmissionTries;
use App\Entity\Marketing\Submission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManager;


class ResubmitErrorSubmittionSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     * e.g.
     *  coreg:resubmit-error-sumbmittion
     *
     * @var string
     */
    protected $signature = 'submission:resubmit-error-submittion
        {--period=6 : how often will the resending in hours}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tries to resubmit submissions with status error_submittion. Set status to pending 3 times per 6 hours by default';

    /**
     * @param EntityManager        $em
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(EntityManager $em)
    {
        $period = $this->option('period');

        $triesRepo = $em->getRepository(CoregResubmissionTries::class);

        $qb = $em->createQueryBuilder();
        $qb->select('s')
            ->from(Submission::class, 's')
            ->leftJoin(CoregResubmissionTries::class, 'tries',  \Doctrine\ORM\Query\Expr\Join::WITH, 's.submissionId = tries.submissionId')
            ->andWhere("s.status = :status")
            ->andWhere('tries.tries > 0 OR tries.tries is null')
            ->andWhere("tries.lastUpdate <= :date or tries.lastUpdate is null")
            ->setParameter('status', Submission::STATUS_ERROR_SUBMISSION)
            ->setParameter('date', (new Carbon())->subHour($period));

        $submissions = $qb->getQuery()->getResult();

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $tries = $triesRepo->findOneBy(['submissionId' => $submission->getSubmissionId()]);
            if(isset($tries)){
                $tries->decreaseTriesNumber();
                $tries->setLastUpdate(Carbon::now());
            }else{
                $tries = new CoregResubmissionTries($submission->getSubmissionId());
            }

            $em->persist($tries);
            $submission->setStatus(Submission::STATUS_PENDING);
        }

        $em->flush();

        $this->info(sprintf('Submissions updated for resend: [ %d ];', count($submissions)));
    }
}
