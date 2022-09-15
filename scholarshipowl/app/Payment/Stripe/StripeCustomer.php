<?php
namespace App\Payment\Stripe;

use App\Entity\Account;

class StripeCustomer
{
    protected $id;

    protected $email;

    protected $username;

    protected $firstName;

    protected $lastName;

    /**
     * @var Account
     */
    protected $account;

    public function __construct($customerProps)
    {
        $this->populate($customerProps);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return StripeCustomer
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return StripeCustomer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return StripeCustomer
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     *
     * @return StripeCustomer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     *
     * @return StripeCustomer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setMetadata($meta){
        $this->populate($meta);
    }

    public function toArray()
    {
        return ['email'    => $this->getEmail(),
                'metadata' => [
                    'username'   => $this->getUsername(),
                    'first_name' => $this->getFirstName(),
                    'last_name'  => $this->getLastName()
                ]];
    }

    protected function populate($data)
    {
        foreach ($data as $key => $value){
            $propName = $this->makeCameCaseMethod($key) ;
            $setterName = 'set'.($propName);
            if(method_exists($this, $setterName)){
                $this->$setterName($value);
            }
        }
    }

    protected function makeCameCaseMethod($field){
        return implode('', array_map('ucfirst', explode('_', $field)));
    }
}