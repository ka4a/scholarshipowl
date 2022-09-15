<?php

use App\Entity\Form;
use App\Entity\Essay;
use App\Entity\EssayFiles;
use App\Entity\ApplicationEssay;
use App\Entity\Scholarship;
use App\Entity\RequirementName;
use App\Entity\RequirementText;
use App\Entity\ApplicationText;
use ScholarshipOwl\Data\Entity\Scholarship\Form as OldForm;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\Common\Collections\Criteria;

class EssayMigrateToRequirementText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('application_text')->truncate();
        DB::table('requirement_text')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $essayRequirementName = RequirementName::findOneBy(['type' => RequirementName::TYPE_TEXT, 'name' => 'Essay']);

        /** @var Essay $essay */
        foreach (\EntityManager::getRepository(Essay::class)->findAll() as $essay) {
            $scholarship = $essay->getScholarship();
            $scholarship->addRequirementText($requirementText = new RequirementText([
                'requirementName' => $essayRequirementName,
                'title' => $essay->getTitle(),
                'description' => $essay->getDescription(),
                'sendType' => $essay->getSendType(),
                'attachmentType' => $essay->getAttachmentType(),
                'attachmentFormat' => $essay->getAttachmentFormat(),
                'allowFile' => $scholarship->getFilesAlowed(),
                'minWords' => $essay->getMinWords(),
                'maxWords' => $essay->getMaxWords(),
                'minCharacters' => $essay->getMinCharacters(),
                'maxCharacters' => $essay->getMaxCharacters(),
            ]));

            \EntityManager::flush($scholarship);

            $essayFiles = \EntityManager::getRepository(EssayFiles::class)->findBy(['essay' => $essay]);
            $applicationEssays = \EntityManager::getRepository(ApplicationEssay::class)->findBy(['essay' => $essay]);
            $applicationText = null;

            if ($scholarship->getApplicationType() === Scholarship::APPLICATION_TYPE_ONLINE) {
                $criteria = Criteria::create()->where(Criteria::expr()->in('systemField', [OldForm::ESSAY, OldForm::UPLOAD_FIELD]));
                /** @var Form[]|\Doctrine\Common\Collections\ArrayCollection $forms */
                $forms = $scholarship->getForms()->matching($criteria);

                if ($forms->count() > 0) {
                    $essayForm = null;
                    if ($forms->count() === 1) {
                        $essayForm = $forms[0];
                    } else {
                        foreach ($forms as $form) {
                            if ($form->getFormField() === $essay->getFieldName()) {
                                $essayForm = $form;
                                break;
                            }
                        }
                    }

                    if ($essayForm) {
                        $essayForm->setSystemField($essayForm->getSystemField() === OldForm::ESSAY ? Form::TEXT : Form::REQUIREMENT_UPLOAD_TEXT);
                        $essayForm->setValue($requirementText->getId());
                    } else {
                        echo sprintf("Requirement text not found for essay %s, scholarship %s\n", $essay->getEssayId(), $scholarship->getScholarshipId());
                    }
                }
            }


            $applicationTexts = [];
            if ($scholarship->getFilesAlowed()) {
                /** @var EssayFiles $essayFile */
                foreach ($essayFiles as $essayFile) {
                    if (isset($applicationTexts[$essayFile->getFile()->getAccount()->getAccountId()])) {
                        $applicationText = new ApplicationText($requirementText, $essayFile->getFile());
                        \EntityManager::persist($applicationText);
                        $applicationTexts[$applicationText->getAccount()->getAccountId()] = $applicationText;
                    }
                }
            }

            /** @var ApplicationEssay $applicationEssay */
            foreach ($applicationEssays as $applicationEssay) {
                if (isset($applicationTexts[$applicationEssay->getAccount()->getAccountId()])) {
                    $applicationTexts[$applicationEssay->getAccount()->getAccountId()]->setText($applicationEssay->getText());
                } else {
                    $applicationText = new ApplicationText($requirementText, null, $applicationEssay->getText(), $applicationEssay->getAccount());
                    \EntityManager::persist($applicationText);
                }
            }
        }

        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('application_text')->truncate();
        DB::table('requirement_text')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        /** @var Form $form */
        $forms = \EntityManager::getRepository(Form::class)->findBy([
            'systemField' => [Form::REQUIREMENT_UPLOAD_TEXT, Form::TEXT],
        ]);

        foreach ($forms as $form) {
            if ($form->getSystemField() === Form::REQUIREMENT_UPLOAD_TEXT) {
                $form->setSystemField(OldForm::UPLOAD_FIELD);
            } elseif ($form->getSystemField() === Form::TEXT) {
                $form->setSystemField(OldForm::ESSAY);
            }
        }

        \EntityManager::flush();
    }
}
