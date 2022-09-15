<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop;

use ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling\InitialMessage;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling\RecurrentMessage;

class MessageFactory
{

    /**
     * @param array $data
     * @param bool|false $isMobile
     * @param string $source
     * @return Message|InitialMessage|RecurrentMessage
     * @throws Exception
     */
    public function generateMessage(array $data, $isMobile = false, $source = null)
    {

        if ($this->isInitialMessage($data)) {
            $message = new InitialMessage($data, $isMobile);

        } elseif ($this->isRecurrentMessage($data)) {
            $message = new RecurrentMessage($data, $isMobile);

        } elseif ($this->isRegularMessage($data)) {
            $message = new Message($data, $isMobile);

        } else {
            throw new Exception("Failed generate payment message.");
        }

        $message->setSource($source);
        return $message;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function isInitialMessage(array $data)
    {
        return array_key_exists(InitialMessage::G2S_REBILLING_INITIAL_TRANSACTION_ID, $data);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function isRecurrentMessage(array $data)
    {
        return array_key_exists(RecurrentMessage::G2S_GATEWAY_TRANSACTION_ID, $data) &&
            array_key_exists(RecurrentMessage::G2S_GATEWAY_TRANSACTION_STATUS, $data);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function isRegularMessage(array $data)
    {
        return array_key_exists(Message::G2S_AMOUNT, $data);
    }

}