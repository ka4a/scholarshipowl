<?php

/**
 * PaymentMethod
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	08. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class PaymentMethod extends AbstractEntity {
	const CREDIT_CARD = 1;
	const PAYPAL = 2;

	private $paymentMethodId;
	private $name;


	public function __construct($paymentMethodId = null) {
		$this->paymentMethodId = null;
		$this->name = "";

		$this->setPaymentMethodId($paymentMethodId);
	}

	public function setPaymentMethodId($paymentMethodId) {
		$this->paymentMethodId = $paymentMethodId;

		$statuses = self::getPaymentMethods();
		if(array_key_exists($paymentMethodId, $statuses)) {
			$this->name = $statuses[$paymentMethodId];
		}
	}

	public function getPaymentMethodId() {
		return $this->paymentMethodId;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public static function getPaymentMethods() {
		return array(
			self::CREDIT_CARD => "Gate2Shop",
			self::PAYPAL => "PayPal",
            \App\Entity\PaymentMethod::BRAINTREE => 'Braintree',
            \App\Entity\PaymentMethod::RECURLY => 'Recurly',
            \App\Entity\PaymentMethod::STRIPE => 'Stripe',
		);
	}

	public function __toString() {
		return $this->name;
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "payment_method_id") {
				$this->setPaymentMethodId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"payment_method_id" => $this->getPaymentMethodId(),
			"name" => $this->getName()
		);
	}
}
