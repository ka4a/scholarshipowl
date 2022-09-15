<?php namespace App\Testing\Traits;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminRole;
use App\Entity\Application;
use App\Entity\ApplicationEssay;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\ApplicationInput;
use App\Entity\ApplicationSpecialEligibility;
use App\Entity\ApplicationStatus;
use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;
use App\Entity\Cms;
use App\Entity\Country;
use App\Entity\Eligibility;
use App\Entity\EligibilityCache;
use App\Services\Mailbox\Email;
use App\Entity\Essay;
use App\Entity\EssayFiles;
use App\Entity\FeatureContentSet;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\Field;
use App\Entity\ForgotPassword;
use App\Entity\Form;
use App\Entity\Log\LoginHistory;
use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Entity\Marketing\Coreg\CoregRequirementsRuleSet;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\CoregPluginAllocation;
use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Package;
use App\Entity\PaymentMethod;
use App\Entity\Popup;
use App\Entity\PopupCms;
use App\Entity\Profile;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementInput;
use App\Entity\RequirementName;
use App\Entity\RequirementSurvey;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Entity\SocialAccount;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Entity\TransactionPaymentType;
use App\Entity\TransactionStatus;
use App\Entity\Domain;
use App\Entity\Winner;
use App\Events\Account\UpdateAccountEvent;
use App\Events\Scholarship\ScholarshipCreatedEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\Account\AccountLoginTokenService;
use App\Services\Account\AccountService;
use App\Services\AccountLoginTokenServic;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ScholarshipOwl\Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;

trait EntityGenerator
{
    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @param int $domain
     * @param bool $generateLoginToken
     * @param bool $fireUpdateAccountEvent
     * @return Account
     */
    public function generateAccount(
        $email = 'test@test.com',
        $firstName = 'testFirstName',
        $lastName = 'testLastName',
        $password = 'testPassword',
        $domain = Domain::SCHOLARSHIPOWL,
        $generateLoginToken = false,
        $fireUpdateAccountEvent = true
    ) {
        static::$truncate[] = 'profile';
        static::$truncate[] = 'ab_test_account';
        static::$truncate[] = 'account';

        /** @var AccountService $accountService */
        $accountService = app(AccountService::class);

        $username = $accountService->generateUsername($email);
        $account = new Account($email, $username, $password, $domain);

        \EntityManager::persist($account);
        \EntityManager::flush($account);

        $account->setProfile(new Profile($firstName, $lastName, Country::USA));

        if ($generateLoginToken) {
            /** @var AccountLoginTokenService $accountLoginTokenService */
            $accountLoginTokenService = app(AccountLoginTokenService::class);
            $accountLoginTokenService->getLatestToken($account);
        }

        \EntityManager::flush($account);

        if ($fireUpdateAccountEvent) {
            \Event::dispatch(new UpdateAccountEvent($account));
        }

        return $account;
    }

    /**
     * @param Account $account
     * @param int $providerUserId
     * @param string $token Provider's(e.g. Faceebook) token
     * @return SocialAccount
     */
    public function generateSocialAccount(Account $account, int $providerUserId, $token = 'token_xxx')
    {
        $socialAccount = new SocialAccount($providerUserId);
        $socialAccount->setToken($token);
        $account->setSocialAccount($socialAccount);

        $this->em->persist($socialAccount);
        $this->em->flush();

        return $socialAccount;
    }


    public function fillProfileData(Profile $profile)
    {
        $profile->setDateOfBirth(new \DateTime('2000-09-06'));
        $profile->setPhone('+12345678900');
        $profile->setGender('male');
        $profile->setCity('New York');
        $profile->setAddress('Street Name 1 apt. 1');
        $profile->setZip('12345');
        $profile->setCountry(Country::USA);
        $profile->setState(1);
        $profile->setCitizenship(1);
        $profile->setEthnicity(1);
        $profile->setSchoolLevel(1);
        $profile->setDegree(1);
        $profile->setDegreeType(1);
        $profile->setEnrollmentYear(2015);
        $profile->setEnrollmentMonth(9);
        $profile->setHighschoolGraduationYear(2009);
        $profile->setHighschoolGraduationMonth(5);
        $profile->setGraduationYear(2014);
        $profile->setGraduationMonth(8);
        $profile->setGpa('test_gpa_value');
        $profile->setCareerGoal(1);
        $profile->setStudyOnline('Yes');
        $profile->setHighschool('Highschool value');
        $profile->setUniversity('University value');
        \EntityManager::flush($profile);

        return $profile;
    }


    /**
     * @param Account     $account
     * @param string|File $file
     *
     * @return AccountFile
     */
    public function generateAccountFile(Account $account = null, $file = 'test_account_file.txt')
    {
        static::$truncate[] = 'account_file';

        $filename = is_string($file) ? $file : $file->getFilename();
        $file = $file instanceof File ? $file : new UploadedFile(__FILE__, $file);
        $account = $account ?: $this->generateAccount();

        $accountFile = new AccountFile($file, $account, $filename);
        \EntityManager::persist($accountFile);
        \EntityManager::flush($accountFile);

        if ($file instanceof File) {
            copy($file, $accountFile->getFileAsTemporary());
        }

        return $accountFile;
    }

    public function generateImage(int $width = 100, int $height = 100, string $extension = 'jpg')
    {
        $file = tempnam(sys_get_temp_dir(), 'image') . '.' . $extension;
        \Image::canvas($width, $height)->save($file);

        return new File($file);
    }

    /**
     * @param string $name
     * @param int    $price
     * @param string $expirationType
     *
     * @return Package
     */
    public function generatePackage($expirationType = Package::EXPIRATION_TYPE_RECURRENT, $name = 'testPackage', $price = 10)
    {
        static::$truncate[] = 'package';

        $package = new Package($name, $price, $expirationType);
        $package->setIsScholarshipsUnlimited(true);
        $package->setScholarshipsCount(10);

        \EntityManager::persist($package);
        \EntityManager::flush($package);

        return $package;
    }

    /**
     * @param Package $package
     * @param Account $account
     * @param int     $paymentMethod
     * @param int     $acquiredType
     *
     * @return Subscription
     */
    public function generateSubscription(
        Package $package = null,
        Account $account = null,
        $paymentMethod = PaymentMethod::BRAINTREE,
        $acquiredType = SubscriptionAcquiredType::PURCHASED
    ) {
        static::$truncate[] = 'subscription';

        $package = $package ?: $this->generatePackage();
        $account = $account ?: $this->generateAccount(uniqid('email') . '@test.com');

        $account->addSubscription(
            $subscription = new Subscription(
                $package,
                SubscriptionAcquiredType::find($acquiredType),
                PaymentMethod::find($paymentMethod)
            )
        );

        \EntityManager::flush($account);

        return $subscription;
    }

    /**
     * @param Subscription   $subscription
     * @param int            $paymentMethod
     * @param int            $paymentType
     * @param float          $amount
     * @param array          $data
     *
     * @return Transaction
     */
    public function generateTransaction(
        Subscription $subscription = null,
        int          $paymentMethod = PaymentMethod::BRAINTREE,
        int          $paymentType = TransactionPaymentType::CREDIT_CARD,
        float        $amount = 100,
        array        $data = array()
    ) {
        static::$truncate[] = 'transaction';

        $subscription = $subscription ?: $this->generateSubscription();

        $transaction = new Transaction($subscription, $paymentMethod, $paymentType, $amount, $data);

        \EntityManager::persist($transaction);
        \EntityManager::flush($transaction);

        return $transaction;
    }


    /**
     * @param int $status
     * @param int $externalScholarshipId
     * @param int $externalScholarshipTemplateId
     * @param bool $fireUpdateScholarshipEvent
     * @return Scholarship
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateScholarship(
        $status = ScholarshipStatus::PUBLISHED,
        $externalScholarshipId = 0,
        $externalScholarshipTemplateId = 0,
        $fireUpdateScholarshipEvent = true
    )
    {
        static::$truncate[] = 'scholarship';

        $attributes = [
            'title' => 'test',
            'url' => 'test',
            'application_type' => Scholarship::APPLICATION_TYPE_ONLINE,
            'applyUrl' => 'test',
            'amount' => 10,
            'upTo' => 20,
            'awards' => 10,
            'is_active' => true,
            'expirationDate' => Carbon::now()->addDay(1)->toDateTime(),
            '//expirationDate' => now(),
            'formMethod' => Scholarship::FORM_METHOD_POST,
            'formAction' => 'http://example.test/action',
            'status' => ScholarshipStatus::convert($status),
        ];

        if ($externalScholarshipId) {
            $attributes['application_type'] = Scholarship::APPLICATION_TYPE_SUNRISE;
            $attributes['external_scholarship_id'] = $externalScholarshipId;
            $attributes['external_scholarship_template_id'] = $externalScholarshipTemplateId;
        }

        $scholarship = new Scholarship($attributes);

        \EntityManager::persist($scholarship);
        \EntityManager::flush($scholarship);

        if ($fireUpdateScholarshipEvent) {
            \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true, true));
        }

        return $scholarship;
    }
    
    public function generateEligibility(Scholarship $scholarship, $field, $type, $value = null, $isOptional = false)
    {
        static::$truncate[] = 'eligibility';

        $eligibility = new Eligibility($field, $type, $value, $isOptional);

        \EntityManager::flush($scholarship->addEligibility($eligibility));

        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));

        return $eligibility;
    }

    public function generateEligibilities(array $eligibilities)
    {
        return array_map(
            function(array $i) { $this->generateEligibility($i[0], $i[1], $i[2], $i[3] ?? null, $i[4] ?? false); },
            $eligibilities
        );
    }

    public function generateForm(Scholarship $scholarship, $formField, $systemField, $value = null, $mapping = null)
    {
        static::$truncate[] = 'form';

        $form = new Form($formField, $systemField, $value, $mapping);
        $scholarship->addForm($form);
        \EntityManager::flush($scholarship);

        return $form;
    }

    public function generateApplication(
        Scholarship  $scholarship = null,
        Account      $account = null,
        Subscription $subscription = null,
                     $status = ApplicationStatus::NEED_MORE_INFO
    )
    {
        static::$truncate[] = 'application';

        $account = $account ?: $this->generateAccount();
        $scholarship = $scholarship ?: $this->generateScholarship();

        $application = new Application($account, $scholarship, $subscription, $status);

        if ($templateId = $scholarship->getExternalScholarshipTemplateId()) {
            $application->setExternalScholarshipTemplateId($templateId);
        }

        \EntityManager::persist($application);
        \EntityManager::flush($application);
        return $application;
    }

    public function generateApplicationText(
        RequirementText $requirementText,
        AccountFile     $accountFile = null,
        string          $text = null,
        Account         $account = null
    )
    {
        static::$truncate[] = 'application_text';

        $applicationText = $accountFile ?
            new ApplicationText($requirementText, $accountFile) :
            new ApplicationText($requirementText, null, $text, $account);

        \EntityManager::persist($applicationText);
        \EntityManager::flush($applicationText);

        return $applicationText;
    }

    public function generateApplicationSpecialEligibility(
        RequirementSpecialEligibility $requirementSpecialEligibility,
        Account $account,
        int $val = 0
    )
    {
        static::$truncate[] = 'application_special_eligibility';

        $applicationSpecialEligibility = new ApplicationSpecialEligibility($requirementSpecialEligibility, $account, $val);

        \EntityManager::persist($applicationSpecialEligibility);
        \EntityManager::flush($applicationSpecialEligibility);

        return $applicationSpecialEligibility;
    }

    public function generateApplicationSurvey(
        RequirementSurvey $requirementSurvey,
        array $answers = null,
        Account $account
    )
    {
        static::$truncate[] = 'application_survey';

        $answers = [
                [
                    'type' => 'radio',
                    'options' =>
                        array (
                            0 => 'red',
                        ),
                    'question' => 'Which colour?',
                ],
                [
                    'type' => 'checkbox',
                    'options' =>
                        array (
                            0 => 'sun',
                            1 => 'sat',
                        ),
                    'question' => 'Favorite days of week?',
                ],
        ];

        $applicationSurvey = new ApplicationSurvey($requirementSurvey, $answers, $account);

        \EntityManager::persist($applicationSurvey);
        \EntityManager::flush($applicationSurvey);

        return $applicationSurvey;
    }

    public function generateApplicationFile(AccountFile $accountFile, RequirementFile $requirementFile)
    {
        static::$truncate[] = 'application_file';

        $applicationFile = new ApplicationFile($accountFile, $requirementFile);

        \EntityManager::persist($applicationFile);
        \EntityManager::flush($applicationFile);

        return $applicationFile;
    }

    public function generateApplicationImage(AccountFile $accountFile, RequirementImage $requirementImage)
    {
        static::$truncate[] = 'application_image';

        $applicationImage = new ApplicationImage($accountFile, $requirementImage);
        \EntityManager::persist($applicationImage);
        \EntityManager::flush($applicationImage);

        return $applicationImage;
    }

    public function generateApplicationInput(RequirementInput $requirementInput, Account $account, string $text = null)
    {
        static::$truncate[] = 'application_input';

        $applicationInput = new ApplicationInput($requirementInput, $account, $text);

        \EntityManager::persist($applicationInput);
        \EntityManager::flush($applicationInput);

        return $applicationInput;
    }


    public function generateRequirementText(
        Scholarship $scholarship = null,
        bool $allowFile = true,
        $requirementName = null,
        $title = 'test',
        $description = 'test'
    ) {
        static::$truncate[] = 'requirement_text';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_TEXT,
            'name' => 'Essay',
        ]);
        $requirementText = new RequirementText([
            'requirementName' => $requirementName,
            'description' => $description,
            'title' => $title,
            'permanentTag' => substr(md5(microtime()), 0, 8),
            'sendType' => RequirementText::SEND_TYPE_ATTACHMENT,
            'attachmentType' => RequirementText::ATTACHMENT_TYPE_DOC,
            'allowFile' => $allowFile,
        ]);
        $scholarship->addRequirementText($requirementText);

        \EntityManager::flush($scholarship);

        return $requirementText;
    }

    /**
     * @param Scholarship|null $scholarship
     * @param null $requirementName
     * @param string $title
     * @param string $description
     * @param null $survey
     * @return RequirementSurvey
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateRequirementSurvey(
        Scholarship $scholarship = null,
        $requirementName = null,
        $title = 'test',
        $description = 'test',
        $survey = null
    ) {
        static::$truncate[] = 'requirement_survey';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_SURVEY
        ]);

        $survey = $survey ?: [
        [
            'type' => 'radio',
            'options' =>
                array (
                    0 => 'red',
                    1 => 'green',
                    2 => 'blue',
                ),
            'question' => 'Which colour?',
        ],
        [
            'type' => 'checkbox',
            'options' =>
                [
                    0 => 'sun',
                    1 => 'mon',
                    2 => 'tue',
                    3 => 'wed',
                    4 => 'thu',
                    5 => 'fri',
                    6 => 'sat',
                ],
            'question' => 'Favorite days of week?',

        ]];

        $requirementSurvey = new RequirementSurvey([
            'requirementName' => $requirementName,
            'description' => $description,
            'title' => $title,
            'permanentTag' => substr(md5(microtime()), 0, 8),
            'survey' => $survey
        ]);
        $scholarship->addRequirementSurvey($requirementSurvey);

        \EntityManager::flush($scholarship);

        return $requirementSurvey;
    }

    public function generateRequirementSpecialEligibility(
        Scholarship $scholarship = null,
        $text = 'test',
        $requirementName = null,
        $title = 'test',
        $description = 'test'
    ) {
        static::$truncate[] = 'requirement_special_eligibility';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_SPECIAL_ELIGIBILITY
        ]);

        $requirementSpecialEligibility = new RequirementSpecialEligibility([
            'requirementName' => $requirementName,
            'description' => $description,
            'title' => $title,
            'permanentTag' => substr(md5(microtime()), 0, 8),
            'text' => $text,
        ]);
        $scholarship->addRequirementSpecialEligibility($requirementSpecialEligibility);

        \EntityManager::flush($scholarship);

        return $requirementSpecialEligibility;
    }

    public function generateRequirementInput(
        Scholarship $scholarship = null,
        $requirementName = null,
        $title = 'test',
        $description = 'test',
        $name = 'Video link'
    ) {
        static::$truncate[] = 'requirement_input';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_INPUT,
            'name' => $name,
        ]);

        $scholarship->addRequirementInput(
            $requirementInput = new RequirementInput($requirementName, $title, substr(md5(microtime()), 0, 8), $description)
        );

        \EntityManager::flush($scholarship);

        return $requirementInput;
    }

    public function generateRequirementFile(
        Scholarship $scholarship = null,
        $requirementName = null,
        $title = 'test',
        $description = 'test'
    )
    {
        static::$truncate[] = 'requirement_file';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_FILE,
            'name' => 'Video',
        ]);

        $scholarship->addRequirementFile($requirementFile = new RequirementFile(
            $requirementName,
            $title,
            $description
        ));

        \EntityManager::flush($scholarship);

        return $requirementFile;
    }

    public function generateRequirementImage(
        Scholarship $scholarship = null,
        $requirementName = null,
        $title = 'test',
        $description = 'testse',
        $externalId = 1 // Sunrise's requirement id
    ) {
        static::$truncate[] = 'requirement_image';

        $scholarship = $scholarship ?: $this->generateScholarship();
        $requirementName = $requirementName ?: RequirementName::findOneBy([
            'type' => RequirementName::TYPE_IMAGE,
            'name' => 'ProfilePic',
        ]);

        $requirementImage = new RequirementImage(
            $requirementName,
            $title,
            $description
        );

        $requirementImage->setExternalId($externalId);

        $scholarship->addRequirementImage($requirementImage);

        \EntityManager::flush($scholarship);

        return $requirementImage;
    }

    /**
     * @return string
     */
    public function generateBase64pdf()
    {
        return "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";
    }

    /**
     * @return string
     */
    public function generateBase64png()
    {
        return "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";
    }

    /**
     * @return Admin
     */
    public function generateAdminAccount()
    {
        static::$truncate[] = 'admin';
        static::$truncate[] = 'admin_role';

        $adminRole = new AdminRole('Root','Super Admin',AdminRole::ROOT);
        $admin = new Admin('test', 'test@test.com', Admin::STATUS_ACTIVE, 'pass', $adminRole);
        $admin->setAccount($this->generateAccount('admin@test.com'));

        \EntityManager::persist($adminRole);
        \EntityManager::persist($admin);
        \EntityManager::flush();

        return $admin;
    }

    /**
     * @param Account $account
     * @param string $folder
     * @param string $subject
     * @param string $body
     * @param string $sender
     * @param string $recipient
     * @param string $message_id
     * @return Email
     */
    public function generateEmail(
        Account $account,
        string $folder      = 'INBOX',
        string $subject     = 'subject',
        string $body        = 'body',
        string $sender      = 'sender@sender.com',
        string $recipient   = 'recipient@recipient.com',
        string $message_id  = null
    ) : Email
    {
        $email = Email::populate([
            'mailbox' => $account->getUsername(),
            'folder' => $folder,
            'subject' => $subject,
            'body' => $body,
            'sender' => $sender,
            'recipient' => $recipient,
            'message_id' => $message_id ?: uniqid('email'),
        ]);

        return $email;
    }

    /**
     * @param string $url
     * @param string $pageName
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param string $author
     *
     * @return Cms
     */
    public function generateCms(
        string $url,
        string $pageName = 'testPage',
        string $title = 'test cms title',
        string $description = 'test cms description',
        string $keywords = 'test cms keywords',
        string $author = 'test cms author'
    )
    {
        static::$truncate[] = 'cms';

        $cms = new Cms($url, $pageName, $author, $title, $description, $keywords);

        \EntityManager::persist($cms);
        \EntityManager::flush($cms);

        return $cms;
    }

    /**
     * @return FeatureSet
     */
    public function generateFeatureSet()
    {
        static::$truncate[] = 'feature_set';
        static::$truncate[] = 'feature_payment_set';
        static::$truncate[] = 'feature_content_set';

        $package1 = $this->generatePackage();
        $package2 = $this->generatePackage(Package::EXPIRATION_TYPE_RECURRENT, 'test2');

        $desktopPaymentSet = new FeaturePaymentSet(PaymentMethod::CREDIT_CARD, 'desktop payment set', 'desktop payment set popup', [$package1->getPackageId(), $package2->getPackageId()]);
        $mobilePaymentSet = new FeaturePaymentSet(PaymentMethod::CREDIT_CARD, 'mobile payment set', 'mobile payment set popup', [$package1->getPackageId(), $package2->getPackageId()]);
        $contentSet = new FeatureContentSet([
            'homepage_header' => 'test',
            'name' => 'test name',
            'register_header' => 'test header',
            'register_heading_text' => 'test heading text',
            'register_subheading_text' => 'test_subheading_text',
            'register_cta_text' => 'register for free',
            'application_sent_title' => 'application sent title',
            'application_sent_description' => 'application sent title',
            'application_sent_content' => 'application sent title',
            'no_credits_title' => 'no_credits title',
            'no_credits_description' => 'no_credits title',
            'no_credits_content' => 'no_credits title',
            'upgrade_block_text' => 'upgrade block text',
            'upgrade_block_link_upgrade' => 'upgrade block link upgrade',
            'upgrade_block_link_vip' => 'upgrade block link vip',
            'hp_double_promotion_flag' => 0,
            'hp_ydi_flag' => 0,
            'hp_cta_text' => 'CHECK FOR SCHOLARSHIPS',
            'register2_heading_text' => 'test heading text',
            'register2_subheading_text' => 'test_subheading_text',
            'register2_cta_text' => 'register for free',

            'register3_heading_text' => 'test heading text 3 ',
            'register3_subheading_text' => 'test_subheading_text 3',
            'register3_cta_text' => 'register for free 3',

            'pp_header_text' => "test_header_text",
            'pp_header_text_2' => "test_header_text2",

            'pp_carousel_items_cnt' => 8,
        ]);
        \EntityManager::persist($desktopPaymentSet);
        \EntityManager::persist($mobilePaymentSet);
        \EntityManager::persist($contentSet);
        \EntityManager::flush();

        $fset = new FeatureSet('new feature set', $desktopPaymentSet, $mobilePaymentSet, $contentSet);

        \EntityManager::persist($fset);
        \EntityManager::flush($fset);

        return $fset;
    }

    /**
     * Generate Redirect Rule
     *
     * @param RedirectRulesSet $redirectRulesSet
     * @param int $active
     * @param string $field
     * @param string $operator
     * @param mixed $value
     *
     * @return RedirectRule
     */
    public function generateRedirectRule(
        $redirectRulesSet,
        $active = 1,
        $field = "school_level_id",
        $operator = RedirectRule::OPERATOR_GREATER_EQUAL,
        $value = 4
    ) {
        $redirectRule = new RedirectRule($field, $operator, $value, $active);
        $redirectRulesSet->addRedirectRule($redirectRule);

        \EntityManager::persist($redirectRule);
        \EntityManager::flush();

        return $redirectRule;
    }

    /**
     * @param CoregRequirementsRuleSet $coregRequirementsRuleSet
     * @param int                      $active
     * @param string                   $field
     * @param string                   $operator
     * @param int                      $value
     * @param int                      $send
     *
     * @return CoregRequirementsRuleSet
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateCoregRequirementsRule(
        CoregRequirementsRuleSet $coregRequirementsRuleSet,
        $active = 1,
        $field = "school_level_id",
        $operator = CoregRequirementsRule::OPERATOR_GREATER_EQUAL,
        $value = 4,
        $send = 0
    ) {
        $coregRequirementsRule = new CoregRequirementsRule($field, $operator, $value, $active, $send);
        $coregRequirementsRuleSet->setCoregRequirementsRule([$coregRequirementsRule]);
        $coregRequirementsRule->setCoregRequirementsRuleSet($coregRequirementsRuleSet);
        \EntityManager::persist($coregRequirementsRule);
        \EntityManager::flush();

        return $coregRequirementsRule;
    }

    /**
     * Generate Redirect Rules Set
     *
     * @param string $type
     */
    public function generateRedirectRulesSet($type = RedirectRulesSet::TYPE_ALL)
    {
        static::$truncate[] = "redirect_rules_set";
        static::$truncate[] = "redirect_rule";

        $redirectRulesSet = new RedirectRulesSet();

        $redirectRulesSet->setName("Test");
        $redirectRulesSet->setTableName(RedirectRulesSet::TABLE);
        $redirectRulesSet->setType($type);

        \EntityManager::persist($redirectRulesSet);
        \EntityManager::flush($redirectRulesSet);

        return $redirectRulesSet;
    }

    /**
     * Generate Coreg Requirements Rule
     *
     * @param string $type
     */
    public function generateCoregRequirementsRuleSet($type = CoregRequirementsRuleSet::TYPE_ALL)
    {
        static::$truncate[] = "coreg_requirements_rule_set";
        static::$truncate[] = "coreg_requirements_rule";

        $redirectRulesSet = new CoregRequirementsRuleSet();

        $redirectRulesSet->setTableName(CoregRequirementsRuleSet::TABLE);
        $redirectRulesSet->setType($type);

        \EntityManager::persist($redirectRulesSet);
        \EntityManager::flush($redirectRulesSet);

        return $redirectRulesSet;
    }

    /**
     * Generate Coreg plugin
     *
     * @param string $displayPosition
     * @param int $monthlyCap
     * @param bool $isVisible
     * @param CoregRequirementsRuleSet $coregRequirementsRuleSet
     *
     * @return CoregPlugin
     */
    public function generateCoregPlugin(
        string $displayPosition,
        bool $isVisible = true,
        int $monthlyCap = 0,
        CoregRequirementsRuleSet $coregRequirementsRuleSet = null
    ) {
        static::$truncate[] = "coreg_plugins";
        static::$truncate[] = "coreg_plugin_allocation";
        $coregPlugin = new CoregPlugin();
        $coregPlugin->setName("Test");
        $coregPlugin->setText("Test");
        $coregPlugin->setDisplayPosition($displayPosition);
        $coregPlugin->setIsVisible($isVisible);
        $coregPlugin->setMonthlyCap($monthlyCap);
        $coregPlugin->setJustCollect(false);

        if(is_null($coregRequirementsRuleSet)){
            $coregRequirementsRuleSet =  new CoregRequirementsRuleSet();
        }
        $coregPlugin->setCoregRequirementsRuleSet($coregRequirementsRuleSet);

        \EntityManager::persist($coregPlugin);
        \EntityManager::flush($coregPlugin);

        return $coregPlugin;
    }

    /**
     * Generate monthly allocation for coreg plugin
     *
     * @param CoregPlugin $coregPlugin
     * @param string $type
     *
     * @return CoregPluginAllocation
     */
    public function generateCoregPluginAllocation($coregPlugin, $type = "month")
    {
        $allocation = new CoregPluginAllocation();

        $allocation->setCoregPlugin($coregPlugin);
        $allocation->setType($type);
        $allocation->setDate(new Carbon("first day of this month"));

        \EntityManager::persist($allocation);
        \EntityManager::flush($allocation);

        return $allocation;
    }

    public function generateReferred(Account $referral, Account $referred)
    {
        static::$truncate[] = 'referral';

        \DB::table('referral')->insert([[
            'referral_account_id' => $referred->getAccountId(),
            'referred_account_id' => $referral->getAccountId(),
            'referral_channel' => 'Link'
        ]]);
    }

    /**
     * @param $popupProps
     *
     * @return Popup
     */
    public function generatePopup($popupProps)
    {
        static::$truncate[] = 'popup';
        static::$truncate[] = 'popup_cms';

        $popup = new Popup(
            $popupProps['popupDisplay'],
            $popupProps['popupTitle'],
            $popupProps['popupText'],
            $popupProps['popupType'],
            $popupProps['popupTargetId'],
            $popupProps['popupDelay'],
            $popupProps['popupDisplayTimes'],
            $popupProps['startDate'],
            $popupProps['endDate']
        );

        $popup->setRuleSet($popupProps['ruleSet']);

        \EntityManager::persist($popup);
        \EntityManager::flush($popup);

        return $popup;
    }

    /**
     * @return RedirectRulesSet
     */
    public function generateRuleSet()
    {
        static::$truncate[] = "redirect_rules_set";
        static::$truncate[] = "redirect_rule";

        $redirectRuleSet = new RedirectRulesSet();
        $redirectRuleSet->setName("Test");
        $redirectRuleSet->setTableName(RedirectRulesSet::TABLE);
        $redirectRuleSet->setType(RedirectRulesSet::TYPE_ALL);
        $redirectRuleSet->addRedirectRule(new RedirectRule('country_id', RedirectRule::OPERATOR_EQUAL, 1, true));

        \EntityManager::persist($redirectRuleSet);
        \EntityManager::flush($redirectRuleSet);

        return $redirectRuleSet;
    }

    public function generatePopupCms(Popup $popup, $cmspage = 'test-rest')
    {
        $cms = $this->generateCms('/'.$cmspage);
        $popupCms = new PopupCms($popup->getPopupId(), $cms->getCmsId());
        \EntityManager::persist($popupCms);
        \EntityManager::flush($popupCms);
    }

    /**
     * @param Scholarship|null $scholarship
     * @param Account|null $account
     * @return Winner
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateWinner(Scholarship $scholarship = null, Account $account = null)
    {
        static::$truncate[] = 'winner';

            $winner = new Winner();
            $data["title"] = "Add Winner";

            if ($scholarship) {
                $winner->setScholarship($scholarship);
            }

            if ($account) {
                $winner->setAccount($account);
            }

            $winner->setScholarshipTitle('Awesome scholarship!');
            $winner->setAmountWon(1000);
            $winner->setWonAt(new \DateTime());
            $winner->setWinnerName('John Doe');
            $winner->setTestimonialText('Winner\'s Testimonial text');
            $winner->setTestimonialVideo('Winner\'s Testimonial Youtube URL');
            $winner->setPublished(true);
            $winner->setWinnerPhoto('https://some-url-to-get-winners-photo');

            \EntityManager::persist($winner);
            \EntityManager::flush($winner);

            return $winner;
    }

    /**
     * @param Account $account
     * @param int $ttl Days
     * @return ForgotPassword
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generatePasswordResetToken(Account $account, $ttl = 7): ForgotPassword
    {
        $token = md5(sprintf('%s_%s', $account->getEmail(), time()));
        $expire = Carbon::now()->addDays($ttl);
        $forgotPassword = new ForgotPassword($account, $token, $expire);

        \EntityManager::persist($forgotPassword);
        \EntityManager::flush($forgotPassword);

        return $forgotPassword;
    }

    /**
     * @param Account $account
     * @return EligibilityCache
     */
    public function generateEligibleCache(Account $account)
    {
        /**
         * @var EligibilityCache $eligibilityCache
         */

        $eligibilityCache = new EligibilityCache();

        $eligibilityCache->setAccount($account);
        $eligibilityCache->setLastShownScholarshipIds(json_encode(['1' => 1]));
        $eligibilityCache->setEligibleAmount(1);
        $eligibilityCache->setEligibleScholarshipIds(json_encode(['1' => 1]));
        $eligibilityCache->setEligibleCount(1);

        \EntityManager::persist($eligibilityCache);
        \EntityManager::flush($eligibilityCache);

        return $eligibilityCache;
    }

    /**
     * @param Account $account
     */
    public function generateLoginHistory(Account $account)
    {
        static::$truncate[] = 'login_history';

        $item = new LoginHistory(
            $account,
            'login',
            'Default',
            'local',
            '127.0.0.1',
            'PhpUnit'
        );

        \EntityManager::persist($item);
        \EntityManager::flush($item);
    }
}
