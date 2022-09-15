<?php
namespace App\Entity\ApplyMe;

use App\Entity\Account;
use App\Entity\PaymentMethod;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ApplymePayments
 *
 * @ORM\Table(name="applyme_payments", indexes={@ORM\Index(name="applyme_payments_account_id_foreign", columns={"account_id"})})
 * @ORM\Entity
 */
class ApplymePayments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sum", type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $sum;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=255, nullable=false)
     */
    protected $response;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=150, nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_method", type="string", length=50, nullable=false)
     */
    protected $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255, nullable=true)
     */
    protected $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    protected $account;

    /**
     * ApplymePayments constructor.
     * @param Account $account
     * @param string $sum
     * @param string $response
     * @param string $status
     * @param string $paymentMethod
     * @param string|null $data
     */
    function __construct(Account $account, string $sum, string $response, string $status, string $paymentMethod = PaymentMethod::CREDIT_CARD, string $data = null)
    {
        $this->setAccount($account);
        $this->setSum($sum);
        $this->setResponse($response);
        $this->setStatus($status);
        $this->setPaymentMethod($paymentMethod);
        if ($data) {
            $this->setData($data);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param string $sum
     */
    public function setSum(string $sum)
    {
        $this->sum = $sum;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data)
    {
        $this->data = $data;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }
}

