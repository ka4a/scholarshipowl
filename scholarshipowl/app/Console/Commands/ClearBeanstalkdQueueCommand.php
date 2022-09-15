<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ClearBeanstalkdQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'queue:beanstalkd:clear {queue : Queue to clear}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear a Beanstalkd queue, by deleting all pending jobs.';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
        $queue = $this->argument('queue');

		$this->info(sprintf('Clearing queue: %s', $queue));

        /** @var Pheanstalk $pheanstalk */
		$pheanstalk = \Queue::connection('beanstalkd')->getPheanstalk();
		$pheanstalk->useTube($queue);
		$pheanstalk->watch($queue);

		while ($job = $pheanstalk->reserve(0)) {
			$pheanstalk->delete($job);
		}

		$this->info('...cleared.');
	}
}
