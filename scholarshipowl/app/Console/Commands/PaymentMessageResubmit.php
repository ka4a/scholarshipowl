<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class PaymentMessageResubmit extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'payment:resubmit';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run commands from log_payment_message.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        \ScholarshipOwl\Domain\Log\PaymentMessage::resubmit($this->argument('messageId'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('messageId', InputArgument::REQUIRED, 'Log payment method Id.'),
		);
	}

}
