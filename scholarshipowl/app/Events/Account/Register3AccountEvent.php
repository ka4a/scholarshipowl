<?php namespace App\Events\Account;

use ScholarshipOwl\Http\JsonModel;

class Register3AccountEvent extends AccountEvent
{
    /**
     * @var JsonModel
     */
    protected $model;

    /**
     * Register3AccountEvent constructor.
     *
     * @param \App\Entity\Account|int $account
     * @param null|JsonModel               $model
     */
    public function __construct($account, JsonModel $model = null)
    {
        parent::__construct($account);

        $this->model = $model;
    }

    /**
     * @return JsonModel
     */
    public function getModel()
    {
        return $this->model;
    }
}
