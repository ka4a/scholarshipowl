<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


/**
 * Changes accounts usernames
 * Should be fired just once which this makes temporary command.
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ChangeAccountsUsernames extends Command {
	protected $name = "accounts:change_username";
	protected $description = "Changes accounts usernames according to format.";
	
	public function handle() {
		$this->info("ChangeAccountsUsernames Started: " . date("Y-m-d h:i:s"));
		
		try {
			$accounts = array();
			
			\DB::beginTransaction();
			
			$beforeFile = storage_path() . "/cache/ChangeAccountsUsernames_before.csv";
			$afterFile = storage_path() . "/cache/ChangeAccountsUsernames_after.csv";
			
			$beforeBuffer = $this->getCSVLine(array("Account ID", "First Name", "Last Name", "Username"));
			$afterBuffer = $this->getCSVLine(array("Account ID", "First Name", "Last Name", "Username"));
				
			
			$sql = "SELECT a.account_id, a.username, p.first_name, p.last_name FROM account a, profile p WHERE a.account_id = p.account_id";
			$result = \DB::select(\DB::raw($sql));
			
			foreach ($result as $row) {
				$beforeBuffer .= $this->getCSVLine(array($row->account_id, $row->first_name, $row->last_name, $row->username));
				
				$firstName = trim(strtolower($row->first_name));
				$lastName = trim(strtolower($row->last_name));
				
				$username = $firstName . $lastName;
				$username = preg_replace("/[^a-zA-Z0-9]+/", "", $username);
				
				if (array_key_exists($username, $accounts)) {
					for ($i = 1; $i < 1000; $i++) {
						$newUsername = $username . $i;
						
						if (!array_key_exists($newUsername, $accounts)) {
							$username = $newUsername;
							break;
						}
					}
				}
				
				$afterBuffer .= $this->getCSVLine(array($row->account_id, $row->first_name, $row->last_name, $username));
				$accounts[$username] = $row->account_id;
			}
			
			foreach ($accounts as $username => $accountId) {
				$sql = "UPDATE account SET username = ? WHERE account_id = ?";
				\DB::statement($sql, array(md5(time()), $accountId));
				
				$sql = "UPDATE account SET username = ? WHERE account_id = ?";
				\DB::statement($sql, array($username, $accountId));
				
				$this->info("Changing for " . $username . " [" . $accountId . "]");
			}
			
			file_put_contents($beforeFile, $beforeBuffer);
			file_put_contents($afterFile, $afterBuffer);
			
			\DB::commit();
		}
		catch (\Exception $exc) {
			\Log::error($exc);
			\DB::rollback();
		}
		
		$this->info("ChangeAccountsUsernames Ended: " . date("Y-m-d h:i:s"));
	}
	
	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}
	
	private function getCSVLine($data) {
		$result = array();
	
		foreach($data as $value) {
			$result[] = "\"" . $value . "\"";
		}
	
		return implode(",", $result) . PHP_EOL;
	}
}
