<?php

namespace ScholarshipOwl\Domain\Payment;

use ScholarshipOwl\Data\Entity\Payment\PaymentMethod;
use ScholarshipOwl\Domain\Payment\Event\PaymentRemoteEvent;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Gate2ShopListener;
use ScholarshipOwl\Domain\Payment\Gate2Shop\MessageFactory as GTSMessageFactory;
use ScholarshipOwl\Domain\Payment\PayPal\PayPalListener;
use ScholarshipOwl\Domain\Payment\PayPal\Message as PayPalMessage;

class PaymentFactory
{

    /**
     * @param array $data
     * @param $paymentMethod
     * @return IMessage
     *
     * @throws Gate2Shop\Exception
     * @throws \Exception
     */
    public static function createMessage(array $data, $paymentMethod)
    {
        switch ($paymentMethod) {

            case PaymentMethod::CREDIT_CARD:
                $messageFactory = new GTSMessageFactory();
                $paymentMessage = $messageFactory->generateMessage($data);
                break;

            case PaymentMethod::PAYPAL:
                $paymentMessage = new PayPalMessage($data);
                break;

            default:
                throw new \Exception(sprintf("Unknown payment method: %s", $paymentMethod));
                break;

        }

        return $paymentMessage;
    }

    /**
     * @param $paymentMethod
     * @return PaymentRemoteEvent
     * @throws \Exception
     */
    public static function createListener($paymentMethod)
    {
        switch ($paymentMethod) {

            case PaymentMethod::CREDIT_CARD:
                $listener = new Gate2ShopListener();
                break;

            case PaymentMethod::PAYPAL:
                $listener = new PayPalListener();
                break;

            default:
                throw new \Exception(sprintf("Unknown payment method: %s", $paymentMethod));
                break;

        }

        return $listener;
    }

}