<?php

namespace App\Jobs;

use App\Entity\Account;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\CoregRequirementsRuleService;
use App\Services\Marketing\RedirectRulesService;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSubmissions implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account $account
     */
    private $accountId;

    /**
     * Create a new job instance.
     *
     * @param int|Account $account
     *
     * @return void
     */
    public function __construct($account)
    {
        $this->accountId = $account instanceof Account ? $account->getAccountId() : $account;
    }

    /**
     * @param EntityManager        $em
     * @param CoregRequirementsRuleService $coregRequirementsRuleService
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(EntityManager $em, CoregRequirementsRuleService $coregRequirementsRuleService)
    {
        $submissions = $em->getRepository(Submission::class)->findBy([
            "account" => $em->getRepository(Account::class)->find($this->accountId),
            "status" => Submission::STATUS_INCOMPLETE
        ]);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            if (Carbon::now()->subDay()->gte(Carbon::instance($submission->getCreatedAt()))) {
                $submission->setStatus(Submission::STATUS_INVALID);
                $submission->setResponse("Submissions expired");
                continue;
            }

            /** @var CoregPlugin $coregPlugin */
            $coregPlugin = $submission->getCoregPlugin();

            // for GossamerScience and CappexDatadeal we should't move submission to Pending status
            if (in_array($coregPlugin->getName(), [CoregPlugin::NAME_GOSSAMERSCIENCE, Submission::NAME_CAPPEXDATADEAL])) {
                continue;
            }

            if (!$coregPlugin) {
                 $coregPlugin = $em->getRepository(CoregPlugin::class)->findOneBy(["name" => $submission->getName()]);
            }

            if ($coregPlugin &&
                $coregPlugin->getCoregRequirementsRuleSet() &&
                !is_null($coregPlugin->getCoregRequirementsRuleSet()[0]->getId())
            ) {
                //check and set pending if user already filled send requirement rules
                if ($coregRequirementsRuleService->checkUserAgainstRules($coregPlugin->getCoregRequirementsRuleSet()[0]->getId(),
                    $this->accountId, true)
                ) {
                    $submission->setStatus(Submission::STATUS_PENDING);
                }
            } else {
                $submission->setStatus(Submission::STATUS_PENDING);
            }
        }

        $em->flush();
    }
}
