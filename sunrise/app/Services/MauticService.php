<?php namespace App\Services;

use App\Entities\Application;
use App\Entities\MauticContact;
use App\Entities\Scholarship;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Mautic\Api\Contacts;
use Mautic\Api\Emails;
use App\Services\MauticService\Api\Smses;

class MauticService
{
    /**
     * Identify if contact win any scholarship.
     */
    const FIELD_WINNER = 'winner';

    const EMAIL_NOTIFICATION_SCHOLARSHIP_PUBLISHED = 'scholarshipPublished';
    const EMAIL_NOTIFICATION_APPLIED_EMAIL = 'appliedEmail';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Contacts
     */
    protected $contacts;

    /**
     * @var Emails
     */
    protected $emails;

    /**
     * @var Smses
     */
    protected $smses;

    /**
     * MauticService constructor.
     * @param EntityManager $em
     * @param Contacts $contacts
     * @param Emails $emails
     * @param Smses $smses
     */
    public function __construct(EntityManager $em, Contacts $contacts, Emails $emails, Smses $smses)
    {
        $this->em = $em;
        $this->contacts = $contacts;
        $this->emails = $emails;
        $this->smses = $smses;
    }

    /**
     * Notify about scholarship become active.
     *
     * @param Scholarship $scholarship
     * @param string $email
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function notifyScholarshipPublished(Scholarship $scholarship, $email)
    {
        $emailId = $this->findEmailId(static::EMAIL_NOTIFICATION_SCHOLARSHIP_PUBLISHED);
        $parameters = $this->scholarshipParams($scholarship);
        $mauticContact = $this->findOrGenerateMauticContactByEmail($email);
        $this->emails->sendToContact($emailId, $mauticContact->getMauticId(), $parameters);

        return $this;
    }

    /**
     * @param Application $application
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function notifyApplied(Application $application)
    {
        if ($application->getSource() === Application::SOURCE_BARN) {
            $this->emails->sendToContact(
                $this->findEmailId(static::EMAIL_NOTIFICATION_APPLIED_EMAIL),
                $this->findOrGenerateMauticContactByEmail($application->getEmail())->getMauticId(),
                $this->scholarshipParams($application->getScholarship())
            );
        }

        return $this;
    }

    /**
     * Notify contact about winning.
     * Send notification email about need to fill advanced details form.
     *
     * @param Application $application
     * @param string $notification
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function notifyWinner(Application $application, $notification)
    {
        $contactId = $this->findOrGenerateMauticContactByEmail($application->getEmail())->getMauticId();

        $params = $this->scholarshipParams($application->getScholarship());
        $params['tokens']['winner_information_url'] = route('winner-information', $application->getId());

        $this->emails->sendToContact($this->findEmailId($notification), $contactId, $params);

        if ($smsId = $this->findSMSId($notification)) {
            $this->smses->sendToContact($smsId, $contactId);
        }

        return $this;
    }

    /**
     * @param Application $application
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markContactAsWinner(Application $application)
    {
        $mauticId = $this->findOrGenerateMauticContactByEmail($application->getEmail())->getMauticId();

        $this->contacts->edit($mauticId, [static::FIELD_WINNER => true]);

        return $this;
    }

    /**
     * Synchronize application entity with mautic entity.
     *
     * @param Application $application
     * @return MauticContact
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function syncApplication(Application $application)
    {
        $mauticContact = $this->findOrGenerateMauticContactByEmail($application->getEmail());
        $this->contacts->edit($mauticContact->getMauticId(), [
            'name'  => $application->getName(),
            'state' => $application->getState() ? $application->getState()->getName() : null,
            'mobile' => phone_format_us($application->getPhone()),
        ]);

        return $mauticContact;
    }

    /**
     * @param Scholarship $scholarship
     * @return array
     */
    protected function scholarshipParams(Scholarship $scholarship)
    {
        $organisation = $scholarship->getTemplate()->getOrganisation();

        return [
            'tokens' => [
                'scholarship_title' => $scholarship->getTitle(),
                'scholarship_description' => $scholarship->getDescription(),
                'scholarship_start' => $scholarship->getStart()->format('m/d/Y'),
                'scholarship_deadline' => $scholarship->getDeadline()->format('m/d/Y'),
                'scholarship_amount' => $scholarship->getAmount(),
                'scholarship_awards' => $scholarship->getAwards(),
                'scholarship_url' => $scholarship->getPublicUrl(),
                'scholarship_pp_url' => $scholarship->getPublicPPUrl(),
                'scholarship_tos_url' => $scholarship->getPublicTOSUrl(),
                'organisation_name' => $organisation->getName(),
                'organisation_business_name' => $organisation->getBusinessName(),
                'organisation_phone' => phone_format_us($organisation->getPhone()),
                'organisation_email' => $organisation->getEmail(),
                'organisation_website' => $organisation->getWebsite(),
                'organisation_address' => sprintf(
                    "%s %s %s %s, %s %s",
                    $organisation->getAddress(),
                    $organisation->getAddress2(),
                    $organisation->getCountry()->getName(),
                    $organisation->getCity(),
                    $organisation->getState() ? $organisation->getState()->getAbbreviation() : null,
                    $organisation->getZip()
                ),
            ]
        ];
    }

    /**
     * @param $email
     * @return MauticContact|object|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findOrGenerateMauticContactByEmail($email)
    {
        $mauticContact = $this->em->getRepository(MauticContact::class)->findOneBy(['email' => $email]);

        if (is_null($mauticContact)) {
            $mauticContact = $this->generateMauticContact($email);
        }

        if (is_null($mauticContact)) {
            throw new \RuntimeException('Failed to fetch mautic contact.');
        }

        return $mauticContact;
    }

    /**
     * @param $email
     * @return MauticContact|object|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateMauticContact($email)
    {
        $contact = $this->contacts->create(['email' => $email]);

        if (!isset($contact['contact']['id'])) {
            throw new \RuntimeException("Error on creating mautic contact. Didn't get id!");
        }

        $mauticContact = new MauticContact();
        $mauticContact->setEmail($email);
        $mauticContact->setMauticId($contact['contact']['id']);

        try {
            $this->em->persist($mauticContact);
            $this->em->flush($mauticContact);
        } catch (UniqueConstraintViolationException $e) {
            /** @var MauticContact $mauticContact */
            $mauticContact = $this->em->getRepository(MauticContact::class)->findOneBy(['email' => $email]);
            if ((int) $contact['contact']['id'] !== $mauticContact->getMauticId()) {
                $this->contacts->delete($contact['contact']['id']);
            }
        }

        return $mauticContact;
    }

    /**
     * @param MauticContact $contact
     * @return MauticContact|object|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function syncContact(MauticContact $contact)
    {
        if (!$this->contactExists($contact->getMauticId())) {
            $this->em->remove($contact);
            $this->em->flush($contact);
            $contact = $this->findOrGenerateMauticContactByEmail($contact->getEmail());
        }

        return $contact;
    }

    /**
     * @param $contactId
     * @return bool
     */
    protected function contactExists($contactId)
    {
        $response = $this->contacts->get($contactId);
        return is_array($response) && isset($response['contact']['id']) && is_numeric($response['contact']['id']);
    }

    /**
     * @param string $mailType
     * @return string
     */
    protected function findEmailId($mailType)
    {
        if (null === ($id = config("sunrise.mautic.emails.$mailType"))) {
            throw new \RuntimeException(sprintf('Email type %s not found.', $mailType));
        }
        return $id;
    }

    /**
     * @param string $notificationType
     * @return null|int
     */
    protected function findSMSId($notificationType)
    {
        return config(sprintf('sunrise.mautic.smses.%s', $notificationType));
    }
}
