<?php

namespace ScholarshipOwl\Domain\Payment\Event;

use ScholarshipOwl\Domain\Payment\IMessage;

/**
 * Interface IRecurring
 *
 * Used for implementation of recurring payments by different payment gateways.
 *
 * @package ScholarshipOwl\Payment
 */
interface PaymentEvents
{
    /**
     * Successfull payment occured.
     * @param $message IMessage
     * @return mixed
     */
    public function subscriptionPayment(IMessage $message);

    /**
     * Recurring profile created.
     * @param $message IMessage
     * @return mixed
     */
    public function subscriptionCreated(IMessage $message);

    /**
     * Recurring profile modified.
     * @return mixed
     */
    // public function subscriptionModified();

    /**
     * Recurring profile end of term.
     * @return mixed
     */
    // public function subscriptionEot();

    /**
     * Recurring profile canceled.
     * @param $message IMessage
     * @return mixed
     */
    public function subscriptionCanceled(IMessage $message);

    /**
     * Recurring payment failed.
     * @param IMessage $message
     * @return mixed
     */
    public function subscriptionFailed(IMessage $message);

    /**
     * Subscription payment refunded
     * @param IMessage $message
     * @return mixed
     */
    public function subscriptionRefunded(IMessage $message);

    /**
     * @param IMessage $message
     * @return mixed
     */
    public function singlePayment(IMessage $message);

    /**
     * @param IMessage $message
     * @return mixed
     */
    public function singleRefunded(IMessage $message);

}
