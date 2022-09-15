<?php namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use \Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait CloudFile
 */
trait CloudFile
{
    /**
     * @var string
     */
    private $moveFile;

    /**
     * @var File
     */
    private $uploadFile;

    /**
     * Full path on cloud resource
     *
     * @var string
     *
     * @ORM\Column(name="path", type="string")
     */
    private $path;

    /**
     * @var File
     */
    private $file;

    /**
     * Get current file visibility
     *
     * @return string
     */
    abstract public function getVisibility(): string;

    /**
     * @return Filesystem
     */
    public function cloud(): Filesystem
    {
        return \Storage::disk('gcs');
    }

    /**
     * @return string|null
     */
    public function getPublicUrl()
    {
        return \Storage::public($this->getPath());
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if ($this->path === null) {
            throw new \RuntimeException('Trying to get empty path!');
        }

        return $this->path;
    }

    /**
     * @param string|null $path
     *
     * @return string
     */
    public function getLocalPath($path = null)
    {
        return storage_path($path ?: $this->getPath());
    }

    /**
     * @param $path
     *
     * @return $this
     */
    protected function setPath($path)
    {
        if ($this->path !== null) {
            $this->bookForRenaming();
        }

        $this->path = $path;

        return $this;
    }

    /**
     * @return File
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFile(): File
    {
        if ($this->file === null) {
            $this->file = file_exists($this->getLocalPath()) ? new File($this->getLocalPath()) :
                $this->saveFileLocaly($this->cloud()->get($this->getPath()));
        }

        return $this->file;
    }

    /**
     * @return File
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFileAsTemporary(): File
    {
        $file = tmp_file('temp_file');
        $ext = trim(strstr(basename($this->getPath()),'.', false), '.');
        $tempDir = sys_get_temp_dir();
        $nameWithExt = $tempDir.'/'.$file->getFilename().'.'.$ext;
        rename($tempDir.'/'.$file->getFilename(), $nameWithExt);
        file_put_contents($nameWithExt, $this->cloud()->get($this->getPath()));

        return $this->createFile($nameWithExt);
    }

    /**
     * Set file for current entity
     *
     * @param File $file
     * @param bool $upload
     *
     * @return $this
     */
    protected function setFile(File $file, $upload = true)
    {
        $this->file = $file;

        if ($upload) $this->uploadFile = $file;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return File
     */
    protected function saveFileLocaly($content)
    {
        $path = $this->getLocalPath();
        $dir = dirname($path);

        if ((!is_dir($dir) && !mkdir($dir, 0770, true)) || false === file_put_contents($path, $content)) {
            throw new \RuntimeException(sprintf('Can\'t save file %s at %a', $this->getPath(), $path));
        }

        return $this->createFile($path);
    }

    /**
     * @return File|__anonymous@3994
     */
    protected function createFile($path) {
        // Create anonymous class to add a getClientMimeType which is required in Laravel 5.6 validation method
        // and available only in the UploadedFile but not in File class.
        return new class($path) extends File {
            public function __construct(string $path, bool $checkPath = true)
            {
                parent::__construct($path, $checkPath);
            }

            public function getClientMimeType()
            {
                return $this->getMimeType();
            }
        };
    }

    /**
     * Should be called before rename or move, so previous path would be saved.
     * And on entity update will be moved.
     *
     * @return $this
     */
    protected function bookForRenaming()
    {
        if ($this->moveFile === null) {
            $this->moveFile = $this->getPath();
        }

        return $this;
    }

    /**
     * Upload file on entity creation
     *
     * @access private
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->uploadFile) {
            $this->uploadFile($this->uploadFile);
            $this->uploadFile = null;
        }
    }

    /**
     * Rename file
     *
     * @access private
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        if ($this->moveFile && $this->moveFile !== $this->getPath()) {
            if ($this->cloud()->exists($this->getPath())) {
                throw new \Exception(sprintf("Can't rename! File %s already exists!", $this->getPath()));
            }

            $this->cloud()->move($this->moveFile, $this->getPath());
            @unlink($this->getLocalPath($this->moveFile));
            $this->moveFile = null;
        }
    }

    /**
     * Remove file from GC
     */
    public function remove(){
        $this->cloud()->delete($this->getPath());
    }
    /**
     * Remove file on entity delete
     * @ORM\PostRemove
     */
    public function postRemove()
    {
        $this->remove();
    }

    /**
     * Define is file public or not.
     *
     * @return bool
     */
    public function isPublic() : bool
    {
        return $this->getVisibility() === Filesystem::VISIBILITY_PUBLIC;
    }

    /**
     * Should throw error if file already exists or should throw an error.
     *
     * @return bool
     */
    public function override() : bool
    {
        return true;
    }

    /**
     * @param File $file
     *
     * @throws \Exception
     */
    private function uploadFile(File $file)
    {
        if (!$this->override() && $this->cloud()->exists($this->getPath())) {
            throw new \RuntimeException(sprintf("File with name `%s` already exists!", $this->getFile()));
        }

        $content = file_get_contents($file);

        if (!$this->cloud()->put($this->getPath(), $content, $this->getVisibility())) {
            throw new \Exception(sprintf("Failed upload file %s to %s", $file, $this->getPath()));
        }
    }
}
