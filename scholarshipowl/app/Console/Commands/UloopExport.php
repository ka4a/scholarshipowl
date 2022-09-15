<?php namespace App\Console\Commands;

use App\Entity\Scholarship;
use Doctrine\Common\Collections\Criteria;
use Illuminate\Console\Command;

use FtpClient\FtpClient;
use FtpClient\FtpException;
use ScholarshipOwl\Data\DateHelper;

/**
 * Export scholarships to ULoop.
 */
class UloopExport extends FtpUploadCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'uloop:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export scholarships to uloop.';

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

        $this->prepareXmlFile($temporaryXmlFile, $scholarships, "1112");

        $this->info(sprintf('-> Uploading to FTP: %s', basename($temporaryXmlFile)));

        $this->uploadFileToFTP(basename($temporaryXmlFile), $temporaryXmlFile, $settings);

        @unlink($temporaryXmlFile);

        $this->info('Finished export');
    }

    protected function getTemporaryFile() {
        return tempnam(sys_get_temp_dir(), 'uloop_export') . '.xml';
    }

    protected function getFTPSettings() {
        $settings =  \Config::get('scholarshipowl.uloop');
        if (empty($settings['ftp']) || !is_array($settings['ftp'])) {
            throw new \Exception("Missing uloop ftp configurations.");
        }

        return $settings;
    }
}
