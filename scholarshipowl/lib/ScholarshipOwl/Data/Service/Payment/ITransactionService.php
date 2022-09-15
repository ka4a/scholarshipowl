<?php

/**
 * ITransactionService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	09. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Transaction;


interface ITransactionService {
	public function getTransaction($transactionId);
	public function getAccountTransactions($accountId);
	public function addTransaction(Transaction $transaction);
	
	public function searchTransactions($params = array(), $limit = "");
	public function getTransactionsDated($startDate = '', $endDate = '');
	
	public function changeTransactionStatus($transactionId, $transactionStatusId);
}
