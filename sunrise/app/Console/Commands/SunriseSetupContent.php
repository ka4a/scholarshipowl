<?php

namespace App\Console\Commands;

use App\Contracts\LegalContentContract;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Repositories\ScholarshipRepository;
use App\Services\ScholarshipManager\ContentManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class SunriseSetupContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunrise:setup:content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContentManager
     */
    protected $cm;

    /**
     * SunriseSetupContent constructor.
     * @param EntityManager $em
     * @param ContentManager $cm
     */
    public function __construct(EntityManager $em, ContentManager $cm)
    {
        parent::__construct();
        $this->em = $em;
        $this->cm = $cm;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Are you want to add content for scholarships missing content?')) {

            /** @var ScholarshipRepository $scholarshipRepository */
            $scholarshipRepository = $this->em->getRepository(Scholarship::class);
            $templateRepository = $this->em->getRepository(ScholarshipTemplate::class);

            /** @var ScholarshipTemplate $template */
            foreach ($templateRepository->findAll() as $template) {
                if ($template->getContents()->isEmpty()) {
                    $template->addDefaultContents();

                    /**
                     * For Positive Rewards scholarships we have different content.
                     */
                    if ($this->isPRScholarship($template)) {
                        $this->setPRLegalContent($template);
                    }

                    $this->em->flush($template);

                    if ($scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template)) {
                        $content = $this->cm->generateScholarshipContent($scholarship, $scholarship->getContent());
                        $scholarship->setContent($content);
                        $this->em->persist($content);
                        $this->em->flush($content);
                    }
                }
            }

        }
    }

    /**
     * @param ScholarshipTemplate $template
     */
    protected function setPRLegalContent(ScholarshipTemplate $template)
    {
        $template->getContentByType(LegalContentContract::TYPE_TERMS_OF_USE)
            ->setContent(file_get_contents(resource_path('legal-templates/positiveRewards/termsOfUse.html')));
        $template->getContentByType(LegalContentContract::TYPE_PRIVACY_POLICY)
            ->setContent(file_get_contents(resource_path('legal-templates/positiveRewards/privacyPolicy.html')));
    }

    /**
     * Is scholarship is PositiveRewards Scholarship
     *
     * @param ScholarshipTemplate $template
     * @return boolean
     */
    protected function isPRScholarship(ScholarshipTemplate $template)
    {
        foreach (SunriseSetupPR::$scholarships as $data) {
            $domain = SunriseSetupPR::preparePRDomain($data['domain']);
            if ($template->getWebsite() && $template->getWebsite()->getDomain() === $domain) {
                return true;
            }
        }
        return false;
    }
}
