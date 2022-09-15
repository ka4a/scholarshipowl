<?php namespace App\Doctrine\Extensions\Uploadable;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Gedmo\Exception\UploadableNoPathDefinedException;
use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use \Gedmo\Exception\UploadableUploadException;
use \Gedmo\Exception\UploadableNoFileException;
use \Gedmo\Exception\UploadableExtensionException;
use \Gedmo\Exception\UploadableIniSizeException;
use \Gedmo\Exception\UploadableFormSizeException;
use \Gedmo\Exception\UploadableFileAlreadyExistsException;
use \Gedmo\Exception\UploadablePartialException;
use \Gedmo\Exception\UploadableNoTmpDirException;
use \Gedmo\Exception\UploadableCantWriteException;
use \Gedmo\Uploadable\UploadableListener as GedmoUploadableListener;

use Illuminate\Filesystem\FilesystemAdapter;

abstract class AbstractListener extends GedmoUploadableListener
{
    /**
     * @return FilesystemAdapter
     */
    abstract public function disk();

    /**
     * Identify if uploaded files are public.
     * @return string
     */
    abstract public function isPublic();

    /**
     * Moves the file to the specified path
     *
     * @param FileInfoInterface|CloudFileInfo     $fileInfo
     * @param string            $path
     * @param bool              $filenameGeneratorClass
     * @param bool              $overwrite
     * @param bool              $appendNumber
     * @param object            $object
     *
     * @return array
     *
     * @throws \Gedmo\Exception\UploadableUploadException
     * @throws \Gedmo\Exception\UploadableNoFileException
     * @throws \Gedmo\Exception\UploadableExtensionException
     * @throws \Gedmo\Exception\UploadableIniSizeException
     * @throws \Gedmo\Exception\UploadableFormSizeException
     * @throws \Gedmo\Exception\UploadableFileAlreadyExistsException
     * @throws \Gedmo\Exception\UploadablePartialException
     * @throws \Gedmo\Exception\UploadableNoTmpDirException
     * @throws \Gedmo\Exception\UploadableCantWriteException
     */
    public function moveFile(FileInfoInterface $fileInfo, $path, $filenameGeneratorClass = false, $overwrite = false, $appendNumber = false, $object)
    {
        if ($fileInfo->getError() > 0) {
            switch ($fileInfo->getError()) {
                case 1:
                    $msg = 'Size of uploaded file "%s" exceeds limit imposed by directive "upload_max_filesize" in php.ini';

                    throw new UploadableIniSizeException(sprintf($msg, $fileInfo->getName()));
                case 2:
                    $msg = 'Size of uploaded file "%s" exceeds limit imposed by option MAX_FILE_SIZE in your form.';

                    throw new UploadableFormSizeException(sprintf($msg, $fileInfo->getName()));
                case 3:
                    $msg = 'File "%s" was partially uploaded.';

                    throw new UploadablePartialException(sprintf($msg, $fileInfo->getName()));
                case 4:
                    $msg = 'No file was uploaded!';

                    throw new UploadableNoFileException(sprintf($msg, $fileInfo->getName()));
                case 6:
                    $msg = 'Upload failed. Temp dir is missing.';

                    throw new UploadableNoTmpDirException($msg);
                case 7:
                    $msg = 'File "%s" couldn\'t be uploaded because directory is not writable.';

                    throw new UploadableCantWriteException(sprintf($msg, $fileInfo->getName()));
                case 8:
                    $msg = 'A PHP Extension stopped the uploaded for some reason.';

                    throw new UploadableExtensionException(sprintf($msg, $fileInfo->getName()));
                default:
                    throw new UploadableUploadException(sprintf('There was an unknown problem while uploading file "%s"',
                        $fileInfo->getName()
                    ));
            }
        }

        $info = array(
            'fileName'          => '',
            'fileExtension'     => '',
            'fileWithoutExt'    => '',
            'origFileName'      => '',
            'filePath'          => '',
            'fileMimeType'      => $fileInfo->getType(),
            'fileSize'          => $fileInfo->getSize(),
        );

        $info['fileName'] = basename($fileInfo->getName());
        $info['filePath'] = $path.'/'.$info['fileName'];

        $hasExtension = strrpos($info['fileName'], '.');

        if ($hasExtension) {
            $info['fileExtension'] = substr($info['filePath'], strrpos($info['filePath'], '.'));
            $info['fileWithoutExt'] = substr($info['filePath'], 0, strrpos($info['filePath'], '.'));
        } else {
            $info['fileWithoutExt'] = $info['fileName'];
        }

        // Save the original filename for later use
        $info['origFileName'] = $info['fileName'];

        // Now we generate the filename using the configured class
        if ($filenameGeneratorClass) {
            $filename = $filenameGeneratorClass::generate(
                str_replace($path.'/', '', $info['fileWithoutExt']),
                $info['fileExtension'],
                $object
            );
            $info['filePath'] = str_replace(
                '/'.$info['fileName'],
                '/'.$filename,
                $info['filePath']
            );
            $info['fileName'] = $filename;

            if ($pos = strrpos($info['filePath'], '.')) {
                // ignores positions like "./file" at 0 see #915
                $info['fileWithoutExt'] = substr($info['filePath'], 0, $pos);
            } else {
                $info['fileWithoutExt'] = $info['filePath'];
            }
        }

        if ($this->disk()->exists($info['filePath'])) {
            if ($overwrite) {
                $this->cancelFileRemoval($info['filePath']);
                $this->removeFile($info['filePath']);
            } elseif ($appendNumber) {
                $counter = 1;
                $info['filePath'] = $info['fileWithoutExt'].'-'.$counter.$info['fileExtension'];

                do {
                    $info['filePath'] = $info['fileWithoutExt'].'-'.(++$counter).$info['fileExtension'];
                } while ($this->disk()->exists($info['filePath']));
            } else {
                throw new UploadableFileAlreadyExistsException(sprintf('File "%s" already exists!',
                    $info['filePath']
                ));
            }
        }

        if (!$this->disk()->putFileAs(dirname($info['filePath']), $fileInfo->file(), basename($info['filePath']), $this->options())) {
            throw new UploadableUploadException(sprintf(
                'File "%s" was not uploaded, or there was a problem moving it to the location "%s".',
                $fileInfo->getName(),
                $path
            ));
        }

        return $info;
    }

    /**
     * @param ClassMetadata $meta
     * @param array         $config
     * @param object        $object Entity
     *
     * @return string
     *
     * @throws UploadableNoPathDefinedException
     */
    protected function getPath(ClassMetadata $meta, array $config, $object)
    {
        $path = $config['path'];

        if ($path === '') {
            $defaultPath = $this->getDefaultPath();
            if ($config['pathMethod'] !== '') {
                $pathMethod = $meta->getReflectionClass()->getMethod($config['pathMethod']);
                $pathMethod->setAccessible(true);
                $path = $pathMethod->invoke($object, $defaultPath);
            } elseif ($defaultPath !== null) {
                $path = $defaultPath;
            } else {
                $msg = 'You have to define the path to save files either in the listener, or in the class "%s"';

                throw new UploadableNoPathDefinedException(
                    sprintf($msg, $meta->name)
                );
            }
        }

        $path = rtrim($path, '\/');

        return $path;
    }

    /**
     * Simple wrapper for the function "unlink" to ease testing
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function removeFile($filePath)
    {
        if ($this->disk()->exists($filePath)) {
            return $this->disk()->delete($filePath);
        }

        return false;
    }

    /**
     * @return array
     */
    private function options()
    {
        return [
            'visibility' => $this->isPublic() ? 'public' : 'private'
        ];
    }
}
