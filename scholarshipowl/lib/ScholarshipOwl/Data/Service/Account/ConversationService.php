<?php

/**
 * ConversationService
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
use ScholarshipOwl\Data\Service\AbstractService;

class ConversationService extends AbstractService implements IConversationService {

	public function getAccountConversations($accountId) {

		$result = array();

		$sql = sprintf("
			SELECT
			c.conversation_id, c.account_id, c.status, c.potential, c.comment, c.last_conversation_date
			FROM %s as c
			WHERE c.account_id = ?
			ORDER BY c.last_conversation_date DESC", self::TABLE_CONVERSATION);

		$resultSet = $this->query($sql, array($accountId));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$ent = new Conversation();
			$ent->populate($row);

			$result[] = $ent;
		}

		return $result;
	}

	public function registerConversation(Conversation $conversation, Account $account) {
		$data = array(
			'account_id' => $account->getAccountId(),
			'status' => $conversation->getStatus(),
			'potential' => $conversation->getPotential(),
			'comment' => $conversation->getComment(),
			'last_conversation_date' => date("Y-m-d H:i:s"),
		);

		$this->insert(self::TABLE_CONVERSATION, $data);
		$conversationId = $this->getLastInsertId();
		$result = $conversationId;
		return $result;
	}

	public function unregisterConversation($conversationId) {
		$result = $this->delete(self::TABLE_CONVERSATION, array('conversation_id' => $conversationId));
		return $result;
	}
	public function getConversation($conversationId) {

		$result = new Conversation();

		$sql = sprintf("
			SELECT c.*
			FROM %s as c
			WHERE c.conversation_id = ?", self::TABLE_CONVERSATION);

		$resultSet = $this->query($sql, array($conversationId));

		foreach($resultSet as $row) {
			$row = (array) $row;

			$result->populate($row);
		}

		return $result;
	}

	public function getConversationInfo($conversationId) {
		$result = null;
		$columns = array(
			"account_id", "status", "potential", "comment", "last_conversation_date",
		);
		$data = $this->getByColumn(self::TABLE_CONVERSATION, "conversation_id", $conversationId, $columns);
		if (!empty($data)) {
			$result = new Conversation();
			$result->populate($data);
		}

		return $result;
	}

	public function setConversationInfo(Conversation $conversation) {

		$result = null;

		$conversationId = $conversation->getConversationId();
		$conversationData = $conversation->toArray();

		$data = array();
		$columns = array(
			"account_id", "status", "potential", "comment", "last_conversation_date",
		);
		foreach($columns as $column) {
			$data[$column] = $conversationData[$column];
		}

		$data["last_conversation_date"] = date("Y-m-d H:i:s");

		$result = $this->update(self::TABLE_CONVERSATION, $data, array("conversation_id" => $conversationId));

		return $result;
	}
	
	public function getLastConversation($accountIds) {
		$result = array();
		
		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}
		
		if (!empty($accountIds)) {
			$marks = implode(array_fill(0, count($accountIds), "?"), ",");
			$conversationIds = array();
			
			$sql = sprintf("
				SELECT MAX(conversation_id) AS conversation_id, account_id
				FROM %s
				WHERE account_id IN(%s)
				GROUP BY account_id
				", self::TABLE_CONVERSATION, $marks
			);
				
			$resultSet = $this->query($sql, $accountIds);
			foreach ($resultSet as $row) {
				$conversationIds[] = $row->conversation_id;
			}
			
			if (!empty($conversationIds)) {
				$marks = implode(array_fill(0, count($conversationIds), "?"), ",");
				
				$sql = sprintf("SELECT * FROM %s WHERE conversation_id IN(%s)", self::TABLE_CONVERSATION, $marks);
				
				$resultSet = $this->query($sql, $conversationIds);
				foreach ($resultSet as $row) {
					$row = (array) $row;
					
					$entity = new Conversation();
					$entity->populate($row);
					
					$result[$entity->getAccountId()] = $entity;
				}
			}
		}
		
		return $result;
	}
}
