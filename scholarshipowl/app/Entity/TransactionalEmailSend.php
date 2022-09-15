<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 27/5/2016
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account;
use App\Entity\TransactionalEmail;

/**
 * Class TransactionalEmailSend
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="transactional_email_send")
 */
class TransactionalEmailSend {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="transactional_email_send_id", type="integer")
     */
    protected $transactional_email_send_id;

    /**
     * @ORM\OneToOne(targetEntity="TransactionalEmail")
     * @ORM\JoinColumn(name="transactional_email_id", referencedColumnName="transactional_email_id")
     */
    protected $transactional_email;

    /**
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    protected $account;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $send_date;

    /**
     * TransactionalEmailSend constructor.
     *
     * @param TransactionalEmail        $transactionalEmail
     * @param Account                   $account
     * @param \DateTime|null            $sendDate
     */
    public function __construct(TransactionalEmail $transactionalEmail, Account $account,  \DateTime $sendDate = null)
    {
        $this->setAccount($account);
        $this->setTransactionalEmail($transactionalEmail);
        $this->setSendDate($sendDate ?: new \DateTime());
    }

    /**
     * @return integer
     */
    public function getTransactionalEmailSendId() {
        return $this->transactional_email_send_id;
    }

    /**
     * @param integer $transactional_email_send_id
     */
    public function setTransactionalEmailSendId($transactional_email_send_id) {
        $this->transactional_email_send_id = $transactional_email_send_id;
    }

    /**
     * @return integer
     */
    public function getTransactionalEmail() {
        return $this->transactional_email;
    }

    /**
     * @param integer $transactional_email
     */
    public function setTransactionalEmail($transactional_email) {
        $this->transactional_email = $transactional_email;
    }

    /**
     * @return integer
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @param integer $account
     */
    public function setAccount($account) {
        $this->account = $account;
    }

    /**
     * @return \DateTime
     */
    public function getSendDate() {
        return $this->send_date;
    }

    /**
     * @param \DateTime $send_date
     */
    public function setSendDate($send_date) {
        $this->send_date = $send_date;
    }

}
