<?php

namespace App\Http\Controllers\Api;

use ScholarshipOwl\Net\Mail\Imap\Mailbox;
use ScholarshipOwl\Net\Mail\Imap\Message;


/**
 * Mailbox Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class MailboxController extends BaseController {
    const CACHE_KEY_PREFIX_MAILBOX_INBOX = "API.MAILBOX.INBOX";
    const CACHE_KEY_PREFIX_MAILBOX_SENT = "API.MAILBOX.SENT";
  	const CACHE_KEY_PREFIX_MAILBOX_COUNT = "API.MAILBOX.COUNT";
    
    private $connection;
    private $mailbox;
    
    
    public function __construct() {
    	parent::__construct();
    	
    	$this->connection = null;
    	$this->mailbox = null;
    }
    
    
    /**
     * Mailbox Search Action - Search Emails (GET)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function searchAction($folder = null) {
        $model = $this->getOkModel("mailbox");
        $username = $this->getLoggedUser()->getUsername();
        $data = array();
        
        
        try {
        	// Assert Folder
        	$folder = $this->getFolderParam($folder);
        	
        	if ($folder == self::ERROR_CODE_MAILBOX_NO_FOLDER || $folder == self::ERROR_CODE_MAILBOX_WRONG_FOLDER) {
        		$model = $this->getErrorModel($folder);
        	}
        	else {
        		$this->openConnection();
        		
        		$this->mailbox = new Mailbox($this->connection, $folder);
        		$this->mailbox->open();
        		
        		
        		// Get Mailbox Message IDs
        		$uids = array();
        		if ($folder == "INBOX") {
        			$uids = $this->mailbox->searchMessages(array("TO" => $username . "@application-inbox.com"), true);
        		}
        		else if ($folder == "INBOX.SENT") {
        			$uids = $this->mailbox->searchMessages(array("FROM" => $username . "@application-inbox.com"), true);
        		}
        		
        		// Get Messages Data
        		$data = $this->getMessages($uids, $folder);
        		$model->setData($data);
        		
        		
        		$this->closeConnection();
        	}
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }
		
        return $model->send();
    }
    
    
    /**
     * Mailbox Read Action - Read Email (GET)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function readAction($folder = null, $uid = null) {
    	$model = $this->getOkModel("mailbox");
    	$username = $this->getLoggedUser()->getUsername();
    	$data = array();
    	
    	
    	try {
    		// Assert Folder & UID
        	$folder = $this->getFolderParam($folder);
        	$uid = $this->getUidParam($uid);
        	
        	if ($folder == self::ERROR_CODE_MAILBOX_NO_FOLDER || $folder == self::ERROR_CODE_MAILBOX_WRONG_FOLDER) {
        		$model = $this->getErrorModel($folder);
        	}
        	else if ($uid == self::ERROR_CODE_MAILBOX_NO_UID) {
        		$model = $this->getErrorModel($uid);
        	}
        	else {
        		$this->openConnection();
        		
        		$this->mailbox = new Mailbox($this->connection, $folder);
        		$this->mailbox->open();
        		
        		
        		// Get Mailbox Message
        		$data = $this->getMessage($uid, $folder);
        		$model->setData($data);
        		
        		
        		$this->closeConnection();
        	}
    	}
    	catch (\Exception $exc) {
    		$this->handleException($exc);
    		$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
    	}
    
    	return $model->send();
    }
    
    
    /**
     * Mailbox Count Action - Get Folder Messages Count (GET)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function countAction($folder = null) {
    	$model = $this->getOkModel("mailbox");
    	$username = $this->getLoggedUser()->getUsername();
    	$accountMailbox = $this->getLoggedUser()->getUsername() . "@application-inbox.com";
    	$data = 0;
    	
    	 
    	try {
    		// Assert Folder
    		$folder = $this->getFolderParam($folder);
    		
    		if ($folder == self::ERROR_CODE_MAILBOX_NO_FOLDER || $folder == self::ERROR_CODE_MAILBOX_WRONG_FOLDER) {
    			$model = $this->getErrorModel($folder);
    		}
    		else {
    			// Read From Cache First
    			$cacheKey = self::CACHE_KEY_PREFIX_MAILBOX_COUNT . "." . $folder . "." . $this->getLoggedUser()->getAccountId();
    			$data = \Cache::get($cacheKey);
    			
    			if (empty($data)) {
    				$this->openConnection();
    				
    				$this->mailbox = new Mailbox($this->connection, $folder);
    				$this->mailbox->open();
    				
    				
    				// Get Mailbox Message IDs
    				$uids = array();
    				if ($folder == "INBOX") {
    					$uids = $this->mailbox->searchMessages(array("TO" => $username . "@application-inbox.com"), true);
    				}
    				else if ($folder == "INBOX.SENT") {
    					$uids = $this->mailbox->searchMessages(array("FROM" => $username . "@application-inbox.com"), true);
    				}
    				
    				// Get Mailbox Messages Count
    				foreach ($uids as $uid) {
    					$message = $this->mailbox->getMessage($uid);
    					
    					if (!empty($message)) {
    						$filtered = false;
    						
    						if ($folder == "INBOX") {
    							if ($message->getTo() == $accountMailbox || strpos($message->getTo(), "<" . $accountMailbox . ">")) {
    								$filtered = true;
    							}
    						}
    						else if ($folder == "INBOX.SENT") {
    							if ($message->getFrom() == $accountMailbox || strpos($message->getFrom(), "<" . $accountMailbox . ">")) {
    								$filtered = true;
    							}
    						}
    						
    						// Skip Undelievered
    						if (preg_match("~\b(delivery failed|Undelivered|Returned)\b~i", $message->getSubject())) {
    							continue;
    						}
    						
    						if ($filtered == true) {
    							$data++;
    						}
    					}
    				}
    				
    				// Save To Cache
    				\Cache::put($cacheKey, $data, 60 * 60);
    				
    				
    				$this->closeConnection();
    			}

    			$model->setData($data);
    		}
    	}
    	catch (\Exception $exc) {
    		$this->handleException($exc);
    		$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
    	}
    
    	return $model->send();
    }
    
    
    // Get Message By UID & Folder
    private function getMessage($uid, $folder) {
    	$result = array(
    		"uid" => $uid,
    		"subject" => "",
    		"from" => "",
    		"to" => "",
    		"date" => "",
    		"timestamp" => "",
    		"body" => "",
    	);
    	
    	
    	$message = $this->mailbox->getMessagesWithStructure($uid, true);
    	if (!empty($message)) {
    		$message = $message[0];
    		
    		$result["subject"] = $message->getSubject();
    		$result["from"] = $message->getFrom();
    		$result["to"] = $message->getTo();
    		$result["date"] = date("M d, Y", $message->getTimestamp());
    		$result["timestamp"] = $message->getTimestamp();

            // Clean HTML
            $body = trim($message->getBody());
            if (preg_match("/(?:<body[^>]*>)(.*)<\/body>/isU", $body, $matches)) {
                $body = $matches[1];
            }
            $body = strip_tags(trim($body), "<img><a><br><div><center><p>");
            $body = str_replace('<a', '<a target="_blank"', $body);

    		if (!preg_match("#(?<=<)\w+(?=[^<]*?>)#", $body)) {
    			$result["body"] = nl2br($body);
    		} else {
    			$result["body"] = $body;
    		}
    	}
    	
    	return $result;
    }
    
    
    // Get Messages By UIDs & Folder
    private function getMessages($uids, $folder) {
    	$result = array();
    	$cachePrefix = $folder == "INBOX" ? self::CACHE_KEY_PREFIX_MAILBOX_INBOX : self::CACHE_KEY_PREFIX_MAILBOX_SENT;
    	$accountMailbox = $this->getLoggedUser()->getUsername() . "@application-inbox.com";
    	
    	if (!is_array($uids)) {
    		$uids = array($uids);
    	}
    	
    	foreach ($uids as $uid) {
    		$cacheKey = $cachePrefix . "." . $uid;
    		$data = \Cache::get($cacheKey);
    		
    		if (empty($data)) {
    			$message = $this->mailbox->getMessagesWithStructure($uid, true);
    			
    			if (!empty($message)) {
    				$message = $message[0];
    				$filtered = false;
    				
    				if ($folder == "INBOX") {
    					if ($message->getTo() == $accountMailbox || strpos($message->getTo(), "<" . $accountMailbox . ">")) {
    						$filtered = true;
    					}
    				}
    				else if ($folder == "INBOX.SENT") {
    					if ($message->getFrom() == $accountMailbox || strpos($message->getFrom(), "<" . $accountMailbox . ">")) {
    						$filtered = true;
    					}
    				}
    				
    				
    				if ($filtered == true) {
    					// Skip Undelievered
    					if (preg_match("~\b(delivery failed|Undelivered|Returned)\b~i", $message->getSubject())) {
    						continue;
    					}
    					
    					// Clean HTML
    					$body = trim($message->getBody());
    					if (preg_match("/(?:<body[^>]*>)(.*)<\/body>/isU", $body, $matches)) {
    						$body = $matches[1];
    					}
    					$body = strip_tags(trim($body));
    					$body = preg_replace("#[\n]+#", "\n", $body);
    					$body = preg_replace("#[\r\n]+#", "\r\n", $body);
    					$body = substr(trim($body), 0, 63);
    					
    					$data = array(
    						"uid" => $uid,
    						"subject" => $message->getSubject(),
    						"from" => $message->getFrom(),
    						"to" => $message->getTo(),
    						"date" => date("M d, Y", $message->getTimestamp()),
    						"timestamp" => $message->getTimestamp(),
    						"body" => $body,
    					);
    					
    					\Cache::put($cacheKey, $data, 60 * 60);
    					$result[$uid] = $data;
    				}
    			}
    		}
    		else {
    			$result[$uid] = $data;
    		}
    	}
    	
    	return $result;
    }
    
    
    // Asserts Folder Param
    private function getFolderParam($folder) {
    	$result = null;
    	
    	if (empty($folder)) {
    		$result = self::ERROR_CODE_MAILBOX_NO_FOLDER;
    	}
    	else {
    		$folder = strtoupper($folder);
        	
        	if (!in_array($folder, array("INBOX", "SENT"))) {
        		$result = self::ERROR_CODE_MAILBOX_WRONG_FOLDER;
        	}
        	else {
        		$result = ($folder == "SENT") ? "INBOX.SENT" : $folder;
        	}
    	}
    	
    	return $result;
    }
    
    
    // Asserts UID Param
    private function getUidParam($uid) {
    	$result = null;
    	
    	if (empty($uid)) {
    		$result = self::ERROR_CODE_MAILBOX_NO_UID;
    	}
    	else {
    		$result = $uid;
    	}
    	
    	return $result;
    }
    
    
    // Opens Connection
    private function openConnection() {
    	$this->connection = \App::make("Imap");
    }
    
    
    // Closes Connection
    private function closeConnection() {
    	$this->connection->close();
    }
}
