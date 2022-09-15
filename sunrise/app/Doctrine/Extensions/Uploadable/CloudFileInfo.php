<?php namespace App\Doctrine\Extensions\Uploadable;

use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;

class CloudFileInfo implements FileInfoInterface
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @param File $file
     * @return static
     */
    static public function create(File $file)
    {
        return new static($file);
    }

    /**
     * UploadableFileInfo constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isUploadedFile()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientOriginalName();
        }
        return $this->file->getFilename();
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->file->getType();
    }

    /**
     * @return int
     */
    public function getError()
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getError();
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->file->getPathname();
    }
}
