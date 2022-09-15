<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use ScholarshipOwl\Net\Mail\Imap\Mailbox;


/**
 * Creates accounts mailboxes
 * Should be fired just once which this makes temporary command.
 * CHANGES: Move all to INBOX and INBOX.SENT folders (file limitation per folder)
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class CreateAccountsMailboxes extends Command {
	protected $name = "accounts:create_mailbox";
	protected $description = "Creates accounts mailboxes.";
	
	public function handle() {
		$this->info("CreateAccountsMailboxes Started: " . date("Y-m-d h:i:s"));
		
		try {
			$imap = \App::make("Imap");
			
			$sql = "SELECT account_id, email, username FROM account";
			$result = \DB::select(\DB::raw($sql));
			
			
			foreach ($result as $row) {
				// OLD FOLDERS
				$mailboxInbox = "INBOX." . $row->username;
				$mailboxSent = "INBOX.SENT." . $row->username;
				$mailboxInbox2 = "INBOX." . implode(".", str_split($row->account_id));
				$mailboxSent2 = "INBOX.SENT." . implode(".", str_split($row->account_id));
				
				
				// MOVE OLD FOLDERS
				$this->moveMessages($imap, $mailboxInbox, "INBOX");
				$this->moveMessages($imap, $mailboxSent, "INBOX.SENT");
				$this->moveMessages($imap, $mailboxInbox2, "INBOX");
				$this->moveMessages($imap, $mailboxSent2, "INBOX.SENT");
			}
			
			$imap->close();
		}
		catch (\Exception $exc) {
			$this->error("Error: " . $exc->getMessage());
			\Log::error($exc);
		}
		
		$this->info("CreateAccountsMailboxes Ended: " . date("Y-m-d h:i:s"));
	}
	
	private function moveMessages($imap, $source, $destination) {
		$result = 0;
		
		try {
			$mailboxExists = true;
			$messages = array();
			
			try {
				$mailbox = new Mailbox($imap, $source);
				$mailbox->open();
				$messages = $mailbox->getMessages();
			}
			catch (\Exception $exc) {
				// MAILBOX NOT EXISTS BECAUSE OF FILE LIMITATION (AFTER 14. APRIL)
				$this->info("[MAILBOX NOT EXISTS] [{$source}] Error: " . $exc->getMessage());
				$mailboxExists = false;
			}
				
			if ($mailboxExists == true) {
				foreach ($messages as $message) {
					$mailbox->moveMessage($message->getMessageMailboxId(), $destination);
					$this->info("Moved From [{$source}] To [{$destination}]: " . $message->getSubject());
						
					$result++;
				}
				
				try {
					$imap->deleteMailbox($source);
				}
				catch (\Exception $exc) {
					// MAILBOX NOT EXISTS BECAUSE OF FILE LIMITATION (AFTER 14. APRIL)
					$this->info("[MAILBOX NOT DELETED OR NOT EXISTS] [{$source}] Error: " . $exc->getMessage());
				}
			}
		}
		catch (\Exception $exc) {
			$this->error("Error: " . $exc->getMessage());
			\Log::error($exc);
		}
		
		return $result;
	}
	
	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}
}
