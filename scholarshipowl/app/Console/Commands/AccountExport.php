<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AccountExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:export {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = 0;
        $limit = 10000;
        $delimiter = ',';
        $file = $this->argument('file');

        $headers = ['Id', 'Email', 'Phone', 'First Name', 'Last Name', 'Address', 'Zip', 'DOB', 'IP', 'Free Trails', 'Paid Subscriptions'];
        $sql =
        'SELECT a.account_id,
               a.email,
               p.phone,
               p.first_name,
               p.last_name,
               p.address,
               p.zip,
               p.date_of_birth,
               lh.ip_address,
               COUNT(free_trial_end_date) AS free_trial_subscriptions,
               COUNT(subscription_acquired_type_id) AS paid_subscriptions
        FROM account a
        INNER JOIN profile p ON p.account_id = a.account_id
        LEFT JOIN subscription s ON s.account_id = a.account_id
            AND (s.free_trial_end_date IS NOT NULL OR s.subscription_acquired_type_id = 1)
        LEFT JOIN
          (SELECT account_id,
                  ip_address,
                  MIN(loginHistoryId)
           FROM login_history
           GROUP BY account_id) AS lh ON lh.account_id = a.account_id
        GROUP BY a.account_id LIMIT ?, ?';

        $csv = fopen($file, 'w');
        fputcsv($csv, $headers, $delimiter);
        $this->info('Writing CSV file');
        while($result = \DB::select($sql, [$start, $limit])) {

            foreach ($result as $row) {
                fputcsv($csv, json_decode(json_encode($row), true), $delimiter);
            }

            $start += $limit;
            $this->info("Finished part: $start (memory: " . memory_get_usage() . ")");
        }

        $this->info('Finished CSV file');
        fclose($csv);
    }
}
