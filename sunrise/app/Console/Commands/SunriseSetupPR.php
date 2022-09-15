<?php namespace App\Console\Commands;

use App\Entities\Organisation;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipWebsite;
use App\Repositories\ScholarshipRepository;
use App\Services\ScholarshipManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class SunriseSetupPR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunrise:setup:pr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sunrise setup positive rewards scholarships';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * For staging PR scholarship domain is different.
     * This function helps to get staging domain for PR scholarship.
     *
     * @param $domain
     * @return string
     */
    public static function preparePRDomain($domain)
    {
        if (App::environment() === 'staging') {
            $domain = str_replace('.com', '.sunrising.tech', $domain);
            $domain = str_replace('www.', '', $domain);
        }

        return $domain;
    }

    public static $scholarships = [
        [
            'domain' => 'www.everydollarcountsweekly.com',
            'title' => 'Every Dollar Counts Weekly Scholarship',
            'description' => 'When it comes to paying for college tuition, every dollar counts and no scholarship is too small. For this reason, ‘Every Dollar Counts’ strives to help students pay for their education. We are proud to announce that we will award one student per week with a $100 scholarship. The application is as easy as can be. Simply enter your basic information (name, address, etc.), then you are registered. You can only apply once a week must be currently enrolled or will be enrolled within three months of registration. Winners are drawn at random.',
            'headline' => 'Applications for the weekly draw are now open!',
            'intro' => 'Be sure to apply at least once a week for more chances of winning.',
            'amount' => 150,
            'start' => 'Monday',
            'layout' => 'grapes',
            'variant' => 'grapes-navy',
            'gtm' => null,
        ],
        [
            'domain' => 'www.easytoenterweekly.com',
            'title' => 'Easy to Enter Weekly Scholarship',
            'description' => 'Scholarships are like the lottery: you can\'t win if you don\'t play. That’s why we made this EASY scholarship. All you have to do is fill out a short sign-up form to be eligible to win this $100 scholarship. Each week, one winner will be selected in a random draw from among all eligible entries received. You must be 16 years of age or older, and you must either be enrolled now, or will be enrolled within three months of registration of the scholarship.',
            'headline' => 'Enter now for the weekly scholarship draw',
            'intro' => 'Register for free for a chance to win an easy $100 towards your education.',
            'amount' => 200,
            'start' => 'Tuesday',
            'layout' => 'grapes',
            'variant' => 'grapes-orange',
            'gtm' => null,
        ],
        [
            'domain' => 'www.me2weekly.com',
            'title' => 'Me Too Weekly Scholarship',
            'description' => 'Need help paying your college tuition? Me too!
Looking for an easy scholarship? Me too! 
Too lazy to write an essay? Me too!
We get it, college is expensive. That’s why we want to give students the chance to win scholarships too! Applicants must be over the age of 16, currently enrolled, or will be enrolled within three month from now. Each week a new winner will be randomly selected.',
            'headline' => 'Apply now for the Me Too scholarship!',
            'intro' => 'Entries are now being accepted for the weekly scholarship draw. Be sure to return next week for another chance at winning.',
            'start' => 'Wednesday',
            'amount' => 250,
            'layout' => 'grapes',
            'variant' => 'grapes-blue',
            'gtm' => null,
        ],
        [
            'domain' => 'www.noeffortweekly.com',
            'title' => 'No Effort Weekly Scholarship',
            'description' => 'We are aware of how important education is for everyone. This is why we are awarding a weekly scholarship to help students pursue their studies. And because we know how busy you are, this ‘No Effort’ scholarship is a no-brainer! The scholarship is open to all high school, college and graduate students who are enrolled now, or will be enrolled within three months of registration. Winners are randomly selected. Check back every week for a chance to win the next scholarship.',
            'headline' => 'No effort is needed to apply for this scholarship!',
            'intro' => 'Simply fill out the free registration form below to apply.',
            'start' => 'Thursday',
            'amount' => 300,
            'layout' => 'kiwi',
            'variant' => 'kiwi-navy',
            'gtm' => null,
        ],
        [
            'domain' => 'www.signmeupweekly.com',
            'title' => 'Sign Me Up Weekly Scholarship',
            'description' => 'Whether you’re a current or soon to be college student or graduate student, you’re just a few clicks away from potentially earning money for school. Make sure to check back each week before the next scholarship deadline. The more you apply, the higher your chances at winning. Applicants must be 16 years of age or older, and must be enrolled now, or will be enrolled within six months of registration. Winners are drawn at random.',
            'headline' => 'Yes, sign me up!',
            'intro' => 'We’re now accepting scholarship applications.',
            'start' => 'Friday',
            'amount' => 500,
            'layout' => 'kiwi',
            'variant' => 'kiwi-green',
            'gtm' => null,
        ],
    ];

    /**
     * Create a new command instance.
     * @param EntityManager $em
     * @param ScholarshipManager $sm
     */
    public function __construct(EntityManager $em, ScholarshipManager $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setupPositiveRewardsScholarships();
    }

    /**
     * @return Organisation
     */
    protected function findPositiveRewardsOrganisation()
    {
        $name = 'Positive Rewards';
        if (null === ($organisation = $this->em->getRepository(Organisation::class)->findOneBy(['name' => $name]))) {
            $organisation = new Organisation();
            $organisation->setName($name);
            $this->em->persist($organisation);
            $this->em->flush($organisation);
        }

        return $organisation;
    }

    /**
     * Generate "Positive Rewards" company scholarships.
     */
    protected function setupPositiveRewardsScholarships()
    {
        $organisation = $this->findPositiveRewardsOrganisation();

        foreach (self::$scholarships as $data) {

            $domain = static::preparePRDomain($data['domain']);
            $scholarship = $this->findPRScholarshipTemplateByDomain($domain);

            if (!$scholarship) {
                $scholarship = new ScholarshipTemplate();
                $scholarship->setWebsite(new ScholarshipWebsite());
                $this->em->persist($scholarship);
            } else {
                $message = sprintf('Are you sure you wanna edit scholarship "%s"', $scholarship->getTitle());
                if (!$this->confirm($message, true)) {
                    continue;
                }
            }

            $scholarship->setTitle($data['title']);
            $scholarship->setAmount($data['amount']);
            $scholarship->setDescription($data['description']);
            $scholarship->setOrganisation($organisation);

            $start = new \DateTime($data['start']);
            $start->setTime(0, 0);

            $end = clone $start;
            $end->modify('+6 day');
            $end->setTime(23, 59, 59);

            $scholarship->setStart($start);
            $scholarship->setDeadline($end);
            $scholarship->setRecurringType(ScholarshipTemplate::PERIOD_TYPE_WEEK);
            $scholarship->setRecurringValue(1);

            $scholarship->getWebsite()->setDomain($domain);
            $scholarship->getWebsite()->setLayout($data['layout']);
            $scholarship->getWebsite()->setVariant($data['variant']);
            $scholarship->getWebsite()->setTitle($data['headline']);
            $scholarship->getWebsite()->setIntro($data['intro']);
            $scholarship->getWebsite()->setGtm($data['gtm']);
            $scholarship->getWebsite()->setCompanyName($organisation->getName());

            $this->em->flush();


            /** @var ScholarshipRepository $repository */
            $repository = $this->em->getRepository(Scholarship::class);
            if (null === ($published = $repository->findSinglePublishedByTemplate($scholarship))) {
                if ($this->confirm(sprintf('Do you wanna publish "%s" scholarship?', $scholarship->getTitle()))) {
                    $published = $this->sm->publish($scholarship);
                }
            }

            if ($published) {
                $this->warn(sprintf(
                    'Scholarship "%s" was published at "%s" deadline "%s".',
                    $scholarship->getWebsite()->getUrl(),
                    $published->getStart()->format('F jS, Y'),
                    $published->getDeadline()->format('F jS, Y')
                ));
            }
        }
    }

    /**
     * @param string $domain
     * @return ScholarshipTemplate|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function findPRScholarshipTemplateByDomain($domain)
    {
        /** @var ScholarshipTemplate $scholarship */
        return $this->em->getRepository(ScholarshipTemplate::class)
            ->createQueryBuilder('t')
            ->join('t.website', 'w')
            ->where('w.domain IN(:domain)')
            ->setParameter('domain', $domain)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
