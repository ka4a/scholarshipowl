<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;
use Doctrine\ORM\Query;

class ApplicationEmailChangeBody extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ScholarshipRepository $repository */
        $repository = \EntityManager::getRepository(Scholarship::class);

        $query = $repository->createQueryBuilder('s')
            ->where('s.applicationType = :typeEmail')
            ->leftJoin('s.requirementTexts', 'rt')
            ->leftJoin('s.requirementInputs', 'rit')
            ->setParameter('typeEmail', Scholarship::APPLICATION_TYPE_EMAIL)
            ->getQuery();

        /** @var Scholarship $scholarship */
        foreach (QueryIterator::create($query, 100) as $scholarships) {
            foreach ($scholarships as $scholarship) {

                /** @var \App\Entity\RequirementText $requirementText */
                foreach ($scholarship->getRequirementTexts() as $requirementText) {
                    if ($requirementText->getSendType() === \App\Entity\RequirementText::SEND_TYPE_BODY) {
                        $scholarship->setEmailMessage(
                            $scholarship->getEmailMessage() . PHP_EOL . '[['.$requirementText->getTag().']]'
                        );
                    }
                }

                /** @var \App\Entity\RequirementInput $requirementInput */
                foreach ($scholarship->getRequirementInputs() as $requirementInput) {
                    $scholarship->setEmailMessage(
                        $scholarship->getEmailMessage() . PHP_EOL . '[['.$requirementInput->getTag().']]'
                    );
                }

            }

            \EntityManager::flush();
            \EntityManager::clear();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
                /** @var ScholarshipRepository $repository */
        $repository = \EntityManager::getRepository(Scholarship::class);

        $query = $repository->createQueryBuilder('s')
            ->where('s.applicationType = :typeEmail')
            ->leftJoin('s.requirementTexts', 'rt')
            ->leftJoin('s.requirementInputs', 'rit')
            ->setParameter('typeEmail', Scholarship::APPLICATION_TYPE_EMAIL)
            ->getQuery();

        /** @var Scholarship $scholarship */
        foreach (QueryIterator::create($query) as $scholarships) {
            foreach ($scholarships as $scholarship) {

                /** @var \App\Entity\RequirementText $requirementText */
                foreach ($scholarship->getRequirementTexts() as $requirementText) {
                    $scholarship->setEmailMessage(
                        map_tags($scholarship->getEmailMessage(), [$requirementText->getTag() => ''])
                    );
                }


                /** @var \App\Entity\RequirementInput $requirementInput */
                foreach ($scholarship->getRequirementInputs() as $requirementInput) {
                    $scholarship->setEmailMessage(
                        map_tags($scholarship->getEmailMessage(), [$requirementInput->getTag() => ''])
                    );
                }

            }

            \EntityManager::flush();
            \EntityManager::clear();
        }
    }
}
