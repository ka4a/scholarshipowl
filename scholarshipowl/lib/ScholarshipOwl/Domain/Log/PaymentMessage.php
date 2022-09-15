<?php

namespace ScholarshipOwl\Domain\Log;

use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Domain\Payment\PaymentFactory;

class PaymentMessage
{

    const MESSAGE_STATUS_VERIFIED = 'verified';

    const MESSAGE_STATUS_INVALID = 'invalid';

    const MESSAGE_STATUS_FAILURE = 'failure';

    /**
     * @param array $messageInput
     * @param null $paymentMethod
     * @param string $status
     */
    public static function log(array $messageInput, $paymentMethod = null, $status = self::MESSAGE_STATUS_VERIFIED)
    {
        \DB::table(IDDL::TABLE_LOG_PAYMENT_MESSAGE)->insert(array(
            'payment_method_id' => $paymentMethod,
            'message' => json_encode($messageInput),
            'verified' => $status,
            'created_date' => date("Y-m-d H:i:s"),
        ));
    }

    /**
     * @param $logMessageId
     * @return \ScholarshipOwl\Domain\Payment\IMessage
     * @throws \Exception
     */
    public static function getPaymentMessage($logMessageId)
    {
        $rawMessage = \DB::table(IDDL::TABLE_LOG_PAYMENT_MESSAGE)
            ->where('log_payment_message_id', '=', $logMessageId)
            ->first();

        if ($rawMessage && !empty($rawMessage->payment_method_id) && !empty($rawMessage->message)) {
            $data = (array) json_decode($rawMessage->message);
            $paymentMethod = $rawMessage->payment_method_id;

            return PaymentFactory::createMessage($data, $paymentMethod);
        } else {
            throw new \Exception(sprintf("Message (%s) not found!", $logMessageId));
        }
    }

    /**
     * @param int $messageId
     * @throws \Exception
     */
    public static function resubmit($messageId)
    {
        $message = static::getPaymentMessage($messageId);
        $listener = PaymentFactory::createListener($message->getPaymentMethod());
        $listener->fireEventFromMessage($message);
    }

}