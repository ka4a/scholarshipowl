<?php namespace App\Services\ApplicationService;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationInput;
use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;
use App\Entity\Scholarship;
use App\Entity\RequirementText;
use App\Entity\AccountFile;
use App\Entity\RequirementSurvey;
use App\Mail\ApplicationSentEmail;
use App\Services\Mailbox\MailboxService;
use Illuminate\Mail\Message;
use PhpOffice\PhpWord\Reader\Word2007;
use Symfony\Component\HttpFoundation\File\File;


class ApplicationSenderEmail extends ApplicationSenderAbstract
{
    /**
     * @var Word2007
     */
    protected $word;
    public function __construct()
    {
        $this->word = new Word2007();
    }

    /**
     * @param Scholarship   $scholarship
     * @param Account       $account
     *
     * @return string
     */
    public static function prepareEmailBody(Scholarship $scholarship, Account $account)
    {
        $requirementTags = [];
        $body = $scholarship->getEmailMessage();

        foreach ($scholarship->getApplicationTexts($account) as $applicationText) {
            $requirement = $applicationText->getRequirement();
            if ($requirement->getSendType() === RequirementText::SEND_TYPE_BODY) {
                $requirementTags[$requirement->getTag()] = $applicationText->getText();
                $requirementTags[$requirement->getPermanentTag()] = $applicationText->getText();
            }
        }

        foreach ($scholarship->getApplicationInputs($account) as $applicationInput) {
            $requirement = $applicationInput->getRequirement();
            $requirementTags[$requirement->getTag()] = $applicationInput->getText();
            $requirementTags[$requirement->getPermanentTag()] = $applicationInput->getText();
        }

        foreach ($scholarship->getApplicationSpecialEligibility($account) as $applicationSpecialEligibility) {
            $requirement = $applicationSpecialEligibility->getRequirement();
            $requirementTags[$requirement->getTag()] = $applicationSpecialEligibility->getStyledText();
            $requirementTags[$requirement->getPermanentTag()] = $applicationSpecialEligibility->getStyledText();
        }

        /**
         * @var ApplicationSurvey $applicationSurvey
         */
        foreach ($scholarship->getApplicationSurvey($account) as $applicationSurvey) {
            /**
             * @var RequirementSurvey $requirement
             */
            $requirement = $applicationSurvey->getRequirement();
            $requirementTags[$requirement->getTag()] = $applicationSurvey->getStyledAnswers();
            $requirementTags[$requirement->getPermanentTag()] = $applicationSurvey->getStyledAnswers();
        }

        $body = $account->mapTags($body);
        $body = map_tags($body, $requirementTags);
        $body = nl2br($body);
        $body = \View::make('emails.system.application', compact('body'));

        return $body;
    }

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return array
     */
    public function prepareSubmitData(Scholarship $scholarship, Account $account) : array
    {
        $profile = $account->getProfile();
        $body = static::prepareEmailBody($scholarship, $account);
        $attachments = $this->prepareEmailAttachments($scholarship, $account);

        return [
            'to' => $scholarship->getEmail(),
            'from' => [$account->getInternalEmail(), $profile->getFullName()],
            'subject' => $account->mapTags($scholarship->getEmailSubject()),
            'replyTo' => $scholarship->getSendToPrivate() ? $account->getEmail() : null,
            'attachments' => $attachments,
            'body' => $body,
        ];
    }

    /**
     * @param Scholarship $scholarship
     * @param array $submitData
     * @param Application $application
     * @return mixed|string
     * @throws \Exception
     */
    public function sendApplication(Scholarship $scholarship, array $submitData, Application $application)
    {
        if ($scholarship->getApplicationType() !== Scholarship::APPLICATION_TYPE_EMAIL) {
            throw new \InvalidArgumentException('Can send only email applications!');
        }

        /**
         * @var string $to
         * @var array $from
         * @var string $subject
         * @var string $replyTo
         * @var string $body
         * @var array  $attachments
         */
        extract($submitData);

		\Mail::send(
			['html' => 'emails.user.application'],
			['content' => $body],
			function(Message $message) use ($to, $subject, $from, $replyTo, $attachments) {
				$message->to($to);
				$message->subject($subject);
				$message->from($from[0], $from[1]);

                if ($replyTo) {
                    $message->replyTo($replyTo);
                }

                foreach ($attachments as $attachment) {
                    if (isset($attachment['isWord']) && $attachment['isWord']){
                        $filePath = $attachment['fileContent'] instanceof File ?  $attachment['fileContent'] ->getPathName() : $attachment['fileContent'];
                        $message->attach($filePath, ['as' => $attachment['name'] ]);
                    } else {
                        $message->attachData($attachment['fileContent'], $attachment['name']);
                    }
                }
			}
		);

        /**
         * Store sent email in user's email box
         */
        $this->storeEmailInSentFolder($application, $submitData);

        if (count(\Mail::failures()) > 0) {
		    throw new \Exception("Can't send emails for application");
        }

        return 'SENT';
    }

    /**
     * @param AccountFile|File $file
     * @param string|null      $filename
     *
     * @return array
     */
    public function prepareFileForAttachment($file, string $filename = null, $attachedFile = false) : array
    {
        if ($file instanceof AccountFile) {
            $filename = $filename ?: $file->getFileName();
            $file = $file->getFileContent();
        }

        if ($file instanceof File) {
            $filename = $filename ?: $file->getFilename();
        }

        $isWord = false;
        if($attachedFile){
            $isWord = $this->word->canRead($file);
        }

        return ['name' => $filename, 'fileContent' => $file, 'isWord' => $isWord];
    }

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return array
     */
    protected function prepareEmailAttachments(Scholarship $scholarship, Account $account)
    {
        $attachments = [];

        foreach ($scholarship->getApplicationTexts($account) as $applicationText) {
            if ($applicationText->getRequirement()->getSendType() === RequirementText::SEND_TYPE_ATTACHMENT) {
                $requirementType = $applicationText->getRequirement()->getAttachmentType();

                if(is_null($applicationText->getText())){
                    $requirementType = pathinfo($applicationText->getAccountFile()->getFileName())['extension'];
                }

                $attachments[] = $this->prepareFileForAttachment(
                    $this->getApplicationTextFile($applicationText),
                    $this->prepareFileName($applicationText->getRequirement(), $account, $requirementType),
                    is_null($applicationText->getAccountFile()) ? true : false
                );
            }
        }

        foreach ($scholarship->getApplicationFiles($account) as $applicationFile) {
            $attachments[] = $this->prepareFileForAttachment($applicationFile->getAccountFile());
        }

        foreach ($scholarship->getApplicationImages($account) as $applicationImage) {
            $attachments[] = $this->prepareFileForAttachment($applicationImage->getAccountFile());
        }

        return $attachments;
    }

    /**
     * @param Application $application
     * @param $submitData
     */
    protected function storeEmailInSentFolder(Application $application, $submitData)
    {
        try {
            /** @var MailboxService $service */
            $service = app(MailboxService::class);

            /** @var Account $account */
            $account = $application->getAccount();

            /**
             * @var string $to
             * @var array $from
             * @var string $subject
             * @var string $replyTo
             * @var string $body
             * @var array $attachments
             */
            extract($submitData);

            $email = \App\Services\Mailbox\Email::populate([
                'mailbox' => $account->getUsername(),
                'folder' => \App\Services\Mailbox\Email::FOLDER_SENT,
                'subject' => $subject,
                'body' => $body,
                'sender' => implode(',', $from),
                'recipient' => $to,
                'scholarship_id' => $application->getScholarship()->getScholarshipId()
            ]);

            $service->saveSentEmail($email);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
