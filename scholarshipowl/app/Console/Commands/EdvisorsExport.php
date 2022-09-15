<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib\Net\SFTP;

class EdvisorsExport extends Command
{
    const EDVISORS_DISK = "edvisors";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edvisors:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uploading edvisors daily CSV to partner FTP';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $delimiter = '|';
        $storage = storage_path('coregs/edvisors');
        $file = $storage.'/'.'edvisors-daily-' . date('d-m-Y-his') . '.csv';
        if (!is_dir($storage)) {
            mkdir($storage, 0777, true); // true for recursive create
        }

        try{
            $this->generateCSVFile($file, $delimiter);
        }catch (\Exception $e){
            $this->info(sprintf("Can't create CSV file. Error: %s", $e->getMessage()));
            \Log::error($e);
        }
        $this->uploadCSVtoFTP($file);
    }

    /**
     * @param $file
     * @param $delimiter
     */
    protected function generateCSVFile($file, $delimiter)
    {
        $headers = [
            "account_id",
            "date",
            "first_name",
            "last_name",
            "email",
            "address",
            "address2",
            "city",
            "state",
            "zip",
            "date_of_birth",
            "gender",
            "university",
            "highschool",
            "graduation_year",
            "enrolled",
            "enrollment_year",
            "gpa",
            "program",
            "citizenship",
            "ethnicity",
            "military_affiliation",
            "school_level",
            "ip_address"
        ];

        $result = $this->loadDataFromDB();

        $csv = fopen($file, 'w');
        fputcsv($csv, $headers, $delimiter);
        $this->info(sprintf('Writing CSV file: %s', $file));
        $rows = 0;
        foreach ($result as $row) {
            $rows++;
            fputs($csv, $this->getCSVLine($row, $delimiter));
        }
        $this->info(sprintf('Finished CSV file. Rows number in file %s', $rows));
        fclose($csv);
    }

    /**
     * @param string $file
     */
    protected function uploadCSVtoFTP($file)
    {
        if(file_exists($file)){
            $result = true;
            $this->info('Uploading CSV file to FTP');
            $settings =  \Config::get('filesystems.disks.edvisors');
            $remoteFile = basename($file);
            $localFile = $file;
            try {
                $sftp = new SFTP($settings['host']);
                if (!$sftp->login($settings['username'], $settings['password'])) {
                    throw new \Exception("Login to remote server failed");
                }

                $sftp->put('/'. $remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE);

            } catch (\Exception $exception) {
                $this->info(sprintf("Could not upload file to FTP. Error: %s",
                    $exception->getMessage()));
                \Log::error($exception);
            }

            if ($result) {
                $this->info('Finished uploading CSV file to FTP.');
            }
        }else{
            $this->info("Can't upload file to FTP. File not exist");
        }
    }

    /**
     * @param $data
     * @param $delimiter
     *
     * @return string
     */
    protected function getCSVLine($data, $delimiter)
    {
        $result = [];

        foreach ($data as $value) {
            $result[] = $value;
        }

        return implode($delimiter, $result) . PHP_EOL;
    }

    /**
     * @return array
     */
    protected function loadDataFromDB(): array
    {
        $sql
            = 'SELECT account.account_id,
               DATE_FORMAT(created_date, \'%Y-%m-%d %H:00:00\') AS \'date\',
               profile.first_name,
               profile.last_name,
               account.email,
               profile.address,
               profile.address2,
               profile.city,
               state.name AS \'state\',
               profile.zip,
               profile.date_of_birth,
               profile.gender,
               profile.university,
               profile.highschool,
               profile.graduation_year,
               profile.enrolled,
               profile.enrollment_year,
               profile.gpa,
               degree.name AS \'program\',
               citizenship.name AS \'citizenship\',
               ethnicity.name AS \'ethnicity\',
               military_affiliation.name AS \'military_affiliation\',
               school_level.name AS school_level,
               login_history.ip_address
        FROM account
        LEFT JOIN profile ON profile.account_id = account.account_id
        LEFT JOIN school_level ON school_level.school_level_id = profile.school_level_id
        LEFT JOIN `state` ON `state`.state_id = profile.state_id
        LEFT JOIN military_affiliation ON military_affiliation.military_affiliation_id = profile.military_affiliation_id
        LEFT JOIN ethnicity ON ethnicity.ethnicity_id = profile.ethnicity_id
        LEFT JOIN citizenship ON citizenship.citizenship_id = profile.citizenship_id
        LEFT JOIN degree ON degree.degree_id = profile.degree_id
        LEFT JOIN
          (SELECT login_history.ip_address,
                  login_history.account_id
           FROM login_history
           WHERE login_history.action_date >= date_sub(curdate(), interval 3 week)
             AND login_history.ip_address != \'104.155.92.77\'
           GROUP BY login_history.account_id
           ORDER BY login_history.action_date) AS login_history ON login_history.account_id = account.account_id
        WHERE account.created_date > NOW() - INTERVAL 1 DAY
          AND city IS NOT NULL
          AND address != FALSE
          AND sell_information != 1
          AND zip IS NOT NULL';
        $result = \DB::select($sql);

        return $result;
    }

}
