<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use ScholarshipOwl\Domain\Payment\QueuePaymentMessage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class QueuePaymentMessageRetry extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:payment:retry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry running job from queue payment message. Table: queue_payment_message.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');

        (new QueuePaymentMessage())->retry($id);

        $this->info(sprintf("Queue payment message (%s) re-submited.", $id));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('id', InputArgument::REQUIRED, 'Queue payment message id (queue_payment_message_id)'),
        );
    }

}
