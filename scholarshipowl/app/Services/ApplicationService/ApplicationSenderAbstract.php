<?php namespace App\Services\ApplicationService;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationText;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\File\File;

abstract class ApplicationSenderAbstract implements ApplicationSenderInterface
{
    /**
     * @param ApplicationText $applicationText
     *
     * @return File
     */
    public function getApplicationTextFile(ApplicationText $applicationText)
    {
        if ($applicationText->getRequirement()->getAllowFile() && $applicationText->getAccountFile()) {
            return $applicationText->getAccountFile()->getFileContent();
        } else {
            return \DocumentGenerator::generate(
                $applicationText->getRequirement()->getAttachmentType(),
                $applicationText->getRequirement()->getTitle(),
                $applicationText->getText()
            );
        }
    }

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return Scholarship
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function prepareScholarship(Scholarship $scholarship, Account $account) : Scholarship
    {
        $queryBuilder = \EntityManager::getRepository(Scholarship::class)->createQueryBuilder('s');
        $queryBuilder = ScholarshipRepository::withApplicationRequirements($queryBuilder, $account)
            ->addSelect(['forms'])
            ->leftJoin('s.forms', 'forms')
            ->where('s.scholarshipId = :scholarship')
            ->setParameter('scholarship', $scholarship);

        return $queryBuilder
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getOneOrNullResult();
    }

    /**
     * @param RequirementText $requirementText
     * @param Account         $account
     * @param                 $fileType
     *
     * @return mixed|string
     */
    public function prepareFileName(RequirementText $requirementText, Account $account, $fileType)
    {
        $format = $requirementText->getAttachmentFormat() ?:
            '[[first_name]]_[[last_name]]__[[title]].[[attachment_type]]';

        $mapping = [
            '[[title]]' => $requirementText->getTitle(),
            '[[attachment_type]]' => $fileType,
        ];

        $name = $account->mapTags($format);
        $name = str_replace(array_keys($mapping), array_values($mapping), $name);

        return $name;
    }
}
