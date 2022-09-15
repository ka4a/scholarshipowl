<?php

/**
 * IConversationService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created     24. November 2014.
 * @copyright   Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;


use App\Entity\Account;
use ScholarshipOwl\Data\Entity\Account\Conversation;

interface IConversationService {
	public function getAccountConversations($accountId);
	public function registerConversation(Conversation $conversation, Account $account);
	public function unregisterConversation($conversationId);

	public function getConversation($conversationId);

	public function getConversationInfo($conversationId);
	public function setConversationInfo(Conversation $conversation);
	
	public function getLastConversation($accountIds);
}
