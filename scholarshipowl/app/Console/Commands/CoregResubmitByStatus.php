<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\Submission;
use App\Entity\Repository\EntityRepository;
use App\Services\Marketing\RedirectRulesService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManager;


class CoregResubmitByStatus extends Command
{
    /**
     * The name and signature of the console command.
     * e.g.
     *  coreg:resubmit-by-status --periodDays=10 --status=inactive
     *  coreg:resubmit-by-status --periodDays=10 --accountId=77777
     *  coreg:resubmit-by-status --periodDays=10 --status=inactive --evaluate=1
     *
     * @var string
     */
    protected $signature = 'coreg:resubmit-by-status 
        {--status=inactive : Which status take in account}
        {--periodDays=10 : Maximum Number of days passed from a submission creation}
        {--accountId= : Particular account id}
        {--evaluate=0 : Just a count of submissions matching the criteria will be evaluated and returned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tries to resubmit failed submissions (sets status to "pending" which will be handled further on by another command)';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(EntityManager $em, RedirectRulesService $redirectRulesService)
    {
        $status = $this->option('status');
        $periodDays = $this->option('periodDays');
        $accountId = $this->option('accountId');
        $isEvaluate = (bool)$this->option('evaluate');

        if (!in_array($status, ['inactive', 'invalid', 'error'])) {
            throw new \InvalidArgumentException('Status must be one of: inactive, invalid, error');
        }

        $qb = $em
            ->createQueryBuilder()
            ->select('s')
            ->from(Submission::class, 's')
            ->andWhere("s.status = :status")
            ->andWhere("DATE(s.createdAt) >= :date")
            ->setParameter('status', $status)
            ->setParameter('date', (new Carbon())->addDays(-$periodDays));

        if ($accountId) {
            $account = $em->getRepository(Account::class)->find($accountId);
            $qb->andWhere("s.account = :account");
            $qb->setParameter('account', $account);
        }

        $submissions = $qb->getQuery()->getResult();
        $submissionsCnt = count($submissions);
        $submissionIds = [];

        if ($isEvaluate) {
            $this->info($submissionsCnt);
            exit;
        }

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionIds[] = $submission->getSubmissionId();
            $coregPlugin = $submission->getCoregPlugin();
            if (!$coregPlugin) {
                 $coregPlugin = $em->getRepository(CoregPlugin::class)->findOneBy(["name" => $submission->getName()]);
            }

            if ($coregPlugin && $coregPlugin->getRedirectRulesSet()) {
                $doesMeetRules = $redirectRulesService->checkUserAgainstRules(
                    $coregPlugin->getRedirectRulesSet()->getId(),
                    $submission->getAccount()->getAccountId()
                );

                if ($doesMeetRules) {
                    $submission->setStatus(Submission::STATUS_PENDING);
                }
            }
        }

        $em->flush();

        $notUpdatedSubmissionsCnt = (int)$qb->select("count(s)")->getQuery()->getSingleScalarResult();
        $command = $this->arguments()['command'];

        $this->info(
            sprintf(
                '[ %s ] [ %s ] Submissions selected for resend: [ %d ]; Successfully processed: [ %d ]',
                 date('c'), $command, $submissionsCnt, ($submissionsCnt - $notUpdatedSubmissionsCnt)
            )
        );
    }
}
