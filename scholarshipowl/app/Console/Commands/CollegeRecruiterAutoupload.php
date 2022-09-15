<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use ScholarshipOwl\Data\Entity\Account\Account;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use phpseclib\Net\SFTP;

class CollegeRecruiterAutoupload extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'collegeRecruiter:autoupload {--date= : Report for specific date}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Auto upload daily high school users XML to College Recruiter SFTP.';

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
        $date = new \DateTime($this->option('date') ?: 'now');
		$fileName = "cr_export_" . $date->format("d-m-Y") . ".xml";
		$file = storage_path('framework/cache/' . $fileName);

        $sqlDate = $date ? 'DATE("'.$date->format('Y-m-d').'")' : 'DATE_SUB(CURRENT_DATE, INTERVAL 1 month)';
		$sql = sprintf("SELECT
                    a.account_id
            FROM
                    %s a
                            JOIN
                    %s p ON a.account_id = p.account_id
            WHERE
                    a.domain_id = 1
                            AND a.account_status_id = 3
                            AND a.sell_information != 1
                            AND DATE(a.created_date) = $sqlDate
                            AND p.school_level_id IN (1 , 2, 3, 4)
                            AND (p.city != '' OR p.city IS NOT NULL)
                            AND (p.state_id != '' OR p.state_id IS NOT NULL)
                            AND (p.zip != '' OR p.zip IS NOT NULL)
            GROUP BY a.account_id
            LIMIT 500;",
			\ScholarshipOwl\Data\Service\IDDL::TABLE_ACCOUNT, \ScholarshipOwl\Data\Service\IDDL::TABLE_PROFILE);

		$resultSet = \DB::select(\DB::raw($sql));

        \Log::info("Submitted to College Recruiter: " . count($resultSet));

		$loginHistoryService = new \ScholarshipOwl\Data\Service\Account\LoginHistoryService();
		$accounts = array();

		foreach ($resultSet as $row) {
            $accounts[] =  \EntityManager::getRepository(\App\Entity\Account::class)
                ->findBy(['accountId' => $row->account_id]);
		}

		$xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><users></users>");

        /** @var \App\Entity\Account $account */
        foreach ($accounts as $account) {
			$profile = $account->getProfile();
			$loginHistory = $loginHistoryService->getAccountLoginHistory($account->getAccountId(), "1");

			$user = $xml->addChild("user");
			$user->account_id = $account->getAccountId()?:"NULL";
			$user->email = $account->getEmail()?:"NULL";
			$user->created_date = $account->getCreatedDate()?:"NULL";
			$user->age = $profile->getAge()?:"NULL";
			$user->first_name = $profile->getFirstName()?:"NULL";
			$user->last_name = $profile->getLastName()?:"NULL";
			$user->phone = $profile->getPhone()?:"NULL";
			$user->date_of_birth = $profile->getDateOfBirth()?:"NULL";
			$user->gender = $profile->getGender()?:"NULL";
			$user->citizenship = getinfo("Citizenship", $profile->getCitizenship()->getCitizenshipId());
			$user->ethnicity = getinfo("Ethnicity", $profile->getEthnicity()->getEthnicityId());
			$user->is_subscribed = $profile->isSubscribed()?:"NULL";
			$user->country = getinfo("Country", $profile->getCountry()->getCountryId());
			$user->state = getinfo("State", $profile->getState()->getStateId());
			$user->city = $profile->getCity()?:"NULL";
			$user->address = $profile->getAddress()?:"NULL";
			$user->zip = $profile->getZip()?:"NULL";
			$user->school_level = getinfo("SchoolLevel", $profile->getSchoolLevel()->getSchoolLevelId());
			$user->degree = getinfo("Degree", $profile->getDegree()->getDegreeId());
			$user->degree_type = getinfo("DegreeType", $profile->getDegreeType()->getDegreeTypeId());
			$user->enrollment_year = $profile->getEnrollmentYear()?:"NULL";
			$user->enrollment_month = $profile->getEnrollmentMonth()?:"NULL";
			$user->gpa = $profile->getGpa()?:"NULL";
			$user->career_goal = getinfo("CareerGoal", $profile->getCareerGoal()->getCareerGoalId());
			$user->graduation_year = $profile->getGraduationYear()?:"NULL";
			$user->graduation_month = $profile->getGraduationMonth()?:"NULL";
			$user->study_online = $profile->getStudyOnline()?:"NULL";
			$user->highschool = $profile->getHighSchool()?:"NULL";
			$user->university = $profile->getUniversity()?:"NULL";
			$user->university1 = $profile->getUniversity1()?:"NULL";
			$user->university2 = $profile->getUniversity2()?:"NULL";
			$user->university3 = $profile->getUniversity3()?:"NULL";
			$user->university4 = $profile->getUniversity4()?:"NULL";
			$user->ip_address = (isset($loginHistory[0]) && $loginHistory[0]->getIpAddress()) ?
                $loginHistory[0]->getIpAddress() : "NULL";
		}

        $this->info(sprintf('Saving to file %s', $file));

        if (!$xml->saveXML($file)) {
            throw new \Exception("Failed to write XML file: ". $file);
        }

        $config = \Config::get('scholarshipowl.collegeRecruiter');
//        $sftp = new SFTP($config['host'], $config['port']);
//
//        if (!$sftp->login($config['username'], $config['password'])) {
//            throw new \Exception("Login to remote server failed");
//        }

//        if (!$sftp->put($config['path'].'/'.$fileName, $file, SFTP::SOURCE_LOCAL_FILE)) {
//            throw new \Exception(sprintf('Failed upload %s to %s:%s'));
//        }

    }

}

