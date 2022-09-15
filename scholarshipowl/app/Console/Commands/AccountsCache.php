<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Services\Account\AccountService;


/**
 * Updates accounts cache
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class AccountsCache extends Command {
	protected $name = "accounts:cache";
	protected $description = "Changes accounts usernames according to format.";
	
	
	public function handle() {
		$this->info("AccountsCache Started: " . date("Y-m-d h:i:s"));
		
		try {
			$sql = "SELECT account_id, email, username FROM account";
			$resultSet = \DB::select(\DB::raw($sql));
			
			foreach ($resultSet as $row) {
				\Cache::put(AccountService::CACHE_KEY_ACCOUNT_EMAIL . "." . $row->email, $row->account_id, 24 * 60 * 60);
				\Cache::put(AccountService::CACHE_KEY_ACCOUNT_USERNAME . "." . $row->username, $row->account_id, 24 * 60 * 60);
			}
		}
		catch (\Exception $exc) {
			$this->error("Error: " . $exc->getMessage());
			\Log::error($exc);
		}
		
		$this->info("AccountsCache Ended: " . date("Y-m-d h:i:s"));
	}
	
	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}
}
