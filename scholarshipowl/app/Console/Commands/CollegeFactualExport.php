<?php namespace App\Console\Commands;

use App\Entity\Scholarship;
use Doctrine\Common\Collections\Criteria;
use Illuminate\Console\Command;

use FtpClient\FtpClient;
use FtpClient\FtpException;
use phpseclib\Net\SFTP;
use ScholarshipOwl\Data\DateHelper;

/**
 * Export scholarships to College Factual.
 */
class CollegeFactualExport extends FtpUploadCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'collegeFactual:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export scholarships to College Factual.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting export');

        $scholarships = \EntityManager::getRepository(Scholarship::class)
            ->matching(Criteria::create()->where(Criteria::expr()->andX(
                Criteria::expr()->eq('isActive', true)
            )));

        $temporaryXmlFile = $this->getTemporaryFile();

        $settings = $this->getFTPSettings();

        $this->info(sprintf('-> Building XML file: %s', $temporaryXmlFile));

        $this->prepareXmlFile($temporaryXmlFile, $scholarships, "1195");

        $this->info(sprintf('-> Uploading to $FTP: %s', basename($temporaryXmlFile)));

        $this->uploadFileToFTP(basename($temporaryXmlFile), $temporaryXmlFile, $settings);

        @unlink($temporaryXmlFile);

        $this->info('Finished export');
    }

    protected function getTemporaryFile() {
        return tempnam(sys_get_temp_dir(), 'cfactual_export') . '.xml';
    }

    protected function getFTPSettings() {
        $settings = \Config::get('scholarshipowl.collegeFactual');
        if (empty($settings['sftp']) || !is_array($settings['sftp'])) {
            throw new \Exception("Missing College Factual sftp configurations.");
        }

        return $settings;
    }
}
