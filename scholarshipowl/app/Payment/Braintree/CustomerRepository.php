<?php namespace App\Payment\Braintree;

use App\Entity\Account;

use Braintree\Customer;
use Braintree\Exception\NotFound;

class CustomerRepository
{
    /**
     * Find braintree customer or create new one if not exists on braintree side.
     *
     * @param Account $account
     *
     * @return Customer
     * @throws \Braintree\Exception\NotFound
     */
    public static function find(Account $account): Customer
    {
        try {

            $customer = Customer::find($account->getAccountId());

        } catch (NotFound $e) {

            $result = Customer::create([
                'id'        => $account->getAccountId(),

                'firstName' => $account->getProfile()->getFirstName(),
                'lastName'  => $account->getProfile()->getLastName(),
                'email'     => $account->getEmail(),
                'phone'     => $account->getProfile()->getPhone(),
            ]);

            if (!$result->success || ! ($customer = $result->customer)) {
                throw new NotFound(sprintf("Error on creating braintree customer: %s", $result->message));
            }

        }

        return $customer;
    }
}
