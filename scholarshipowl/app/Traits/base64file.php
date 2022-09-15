<?php

namespace App\Traits;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Illuminate\Database\Capsule\Manager;

trait base64file
{
    public $base64tempFile;

    public function makeFileFromBase64string(string $base64data)
    {
        if ($fileData = base64_decode($base64data)) {
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $fileInfo->buffer($fileData);

            $mimeTypes = [
                'txt' => 'text/plain',
                'html' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',

                // images
                'png' => 'image/png',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'svg' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'mov' => 'video/quicktime',
                'flv' => 'video/x-flv',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'eps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            ];

            $mimeTypes = array_flip($mimeTypes);
            $ext = $mimeTypes[$mimeType] ?? '';

            $tempFile = tempnam(sys_get_temp_dir(), md5($base64data));
            if ($ext) {
                rename($tempFile, $tempFile .= ".{$ext}");
            }

            $this->base64tempFile = $tempFile;

            $handle = fopen($tempFile, 'w');
            fwrite($handle, $fileData);
            fclose($handle);

            $file =  new \Symfony\Component\HttpFoundation\File\File($tempFile);
        } else {
            throw new \InvalidArgumentException('Failed to decode base64 string');
        }

        return $file;
    }
}
