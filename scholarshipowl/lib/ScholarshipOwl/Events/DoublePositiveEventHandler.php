<?php namespace ScholarshipOwl\Events;

use App\Entity\Marketing\DoublePositiveProgram;
use App\Entity\Marketing\Submission;
use App\Events\Account\Register3AccountEvent;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;
use App\Services\Marketing\SubmissionService;

class DoublePositiveEventHandler
{
    /**
     * @var string
     */
	private $pluginName = "doublepositive";

    /**
     * @var Request
     */
    private $request;

    /**
     * DoublePositiveEventHandler constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Register3AccountEvent $event
     * @param EventManager $em
     */
	public function onRegister3(Register3AccountEvent $event, \EntityManager $em)
    {
        $plugin = \Session::get("plugin.".$this->pluginName);
        $account = $event->getAccount();

        if($plugin) {
            $redirectRulesService = new RedirectRulesService();
            if (($plugin->getRedirectRulesSetId() && $redirectRulesService->checkUserAgainstRules($plugin->getRedirectRulesSetId(),
                        $account->getAccountId())) || !$plugin->getRedirectRulesSetId()) {

                $qb = $em->createQueryBuilder();

                $qb->select(array("p"))
                    ->from(DoublePositiveProgram::class, "p")
                    ->where("p.degreeType = :degreeType")
                    ->andWhere("p.states LIKE :state")
                    ->andWhere("p.minHsGradYear <= :minHsGradYear")
                    ->andWhere("p.maxHsGradYear >= :maxHsGradYear")
                    ->setParameters(array(
                        "state" => "%" . $account->getProfile()->getState()->getAbbreviation() . "%",
                        "degreeType" => $account->getProfile()->getDegreeType()->getId(),
                        "minHsGradYear" => $account->getProfile()->getGraduationYear(),
                        "maxHsGradYear" => $account->getProfile()->getGraduationYear()
                    ));

                $programs = $qb->getQuery()->getResult();

                if($programs){
                    $submission = new Submission();
                    $em->persist($submission);

                    $submission->setAccount($account);
                    $submission->setIpAddress(\Request::getClientIp());
                    $submission->setName(Submission::NAME_DOUBLE_POSITIVE);
                    $submission->setStatus(Submission::STATUS_PENDING);
                    $submission->setParams(json_encode([
                        "program" => getinfo("Degree", $account->getProfile()->getDegree()->getId()),
                        "universalLeadID" => $this->request->input("universal_leadid")
                    ]));

                    $em->flush();
                }
            }
        }
	}

    /**
     * @param Dispatcher $events
     */
	public function subscribe(Dispatcher $events)
    {
		$events->listen(Register3AccountEvent::class, static::class . '@onRegister3');
	}
}
