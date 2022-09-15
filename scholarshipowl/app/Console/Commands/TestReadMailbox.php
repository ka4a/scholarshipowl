<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use ScholarshipOwl\Net\Mail\Imap\Mailbox;


/**
 * Test command for reading mailbox
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class TestReadMailbox extends Command {
	protected $name = "mailbox:read";
	protected $description = "Test command for reading mailbox";
	
	public function handle() {
		$this->info("TestReadMailbox Started: " . date("Y-m-d h:i:s"));
		
		try {
			$imap = \App::make("Imap");
			
			$this->readMailbox($imap, "INBOX");
			$this->readMailbox($imap, "INBOX.SENT");
			$this->readMailbox($imap, "INBOX.ACCOUNTS");
			$this->readMailbox($imap, "INBOX.SENT.ACCOUNTS");
			
			$imap->close();
		}
		catch (\Exception $exc) {
			$this->error("Error: " . $exc->getMessage());
			\Log::error($exc);
		}
		
		$this->info("TestReadMailbox Ended: " . date("Y-m-d h:i:s"));
	}
	
	private function readMailbox($imap, $name) {
		$this->info("Reading [{$name}]");
		
		$mailbox = new Mailbox($imap, $name);
		$mailbox->open();
		
		/*
		$messages = $mailbox->getMessages();
		foreach ($messages as $message) {
			$this->info($message->getSubject());
		}
		*/
		
		$this->info("Total: " . $mailbox->getMessagesCount() . PHP_EOL);
	}
	
	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}
}
