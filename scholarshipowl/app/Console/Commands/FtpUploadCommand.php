<?php namespace App\Console\Commands;

use App\Entity\Scholarship;
use FtpClient\FtpClient;
use Illuminate\Console\Command;
use phpseclib\Net\SFTP;
use ScholarshipOwl\Data\DateHelper;

abstract class FtpUploadCommand extends Command{
    abstract protected function getTemporaryFile();
    abstract protected function getFTPSettings();

    /**
     * Prepare tracking URL
     *
     * @param Scholarship $scholarship
     * @param string $affiliateId
     *
     * @return string
     *
     */
    protected function getTrackingLink(Scholarship $scholarship, $affiliateId){
        return 'http://tracking.scholarshipowl.com/aff_c?offer_id=24&aff_id='.$affiliateId.'&url=https://scholarshipowl.com/scholarships/'
            . urlencode(
                $scholarship->getScholarshipPageUrl() .
                "?offer_id={offer_id}&affiliate_id={aff_id}&transaction_id={transaction_id}"
            );
    }

    /**
     * Prepare XML file for upload
     *
     * @param string $file
     * @param array  $scholarships
     * @param string $affiliateId
     *
     * @throws \Exception
     */
    protected function prepareXmlFile($file, $scholarships, $affiliateId)
    {
        $xml = new \DOMDocument();
        $scholarshipsXML = $xml->appendChild($xml->createElement('Scholarships'));

        /** @var Scholarship $scholarship */
        foreach ($scholarships as $scholarship) {

            $expirationDate = $scholarship->getExpirationDate()->format(DateHelper::DEFAULT_FORMAT);
            $link = $this->getTrackingLink($scholarship, $affiliateId);

            $scholarshipXML = $xml->createElement('Scholarship');
            $scholarshipXML->setAttribute('name', $scholarship->getTitle());
            $scholarshipXML->setAttribute('description', $scholarship->getDescription());
            $scholarshipXML->setAttribute('image', $scholarship->getLogoUrl());
            $scholarshipXML->setAttribute('awards', $scholarship->getAwards());
            $scholarshipXML->setAttribute('expiration_date', $expirationDate);
            $scholarshipXML->setAttribute('href', $link);

            foreach ($scholarship->getEligibilities() as $eligibility) {
                $eligibilityXML = $xml->createElement('Eligibility');
                $eligibilityXML->setAttribute('name', $eligibility->getField()->getName());
                $eligibilityXML->setAttribute('type', $eligibility->getType());
                $eligibilityXML->setAttribute('value', $eligibility->getValue());

                $scholarshipXML->appendChild($eligibilityXML);
            }

            $scholarshipsXML->appendChild($scholarshipXML);
        }

        if (!$xml->save($file)) {
            throw new \Exception(sprintf("Failed saving XML file. %s", error_get_last()));
        }

    }

    /**
     * Upload file to ftp
     *
     * @param $remoteFile
     * @param $localFile
     * @param array $settings
     * @throws \Exception
     */
    protected function uploadFileToFTP($remoteFile, $localFile, array $settings)
    {
        try {
            if (isset($settings['sftp'])) {
                $settings = $settings['sftp'];
                $sftp = new SFTP($settings['host'], $settings['port']);

                if (!$sftp->login($settings['username'], $settings['password'])) {
                    throw new \Exception("Login to remote server failed");
                }

                if (!$sftp->put($settings['path'].'/'.$remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
                    throw new \Exception(sprintf('Failed upload file.'));
                }
            } else if(isset($settings['ftp'])) {
                $settings = $settings['ftp'];
                $ftp = new FtpClient();
                $ftp->connect($settings['host']);
                $ftp->login($settings['login'], $settings['password']);
                $ftp->pasv(true);

                if (!$ftp->put($remoteFile, $localFile, FTP_BINARY)) {
                    throw new \Exception("Failed to upload file.");
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception(sprintf(
                "%s\nLocal file: %s\nRemote file: %s\nHost: %s\nUsername: %s",
                $exception->getMessage(),
                $localFile,
                $remoteFile,
                $settings['host'] ?? null ,
                $settings['login'] ?? ($settings['username'] ?? null)
            ));
        }
    }
}
