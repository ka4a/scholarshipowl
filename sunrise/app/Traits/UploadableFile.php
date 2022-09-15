<?php namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Doctrine\Extensions\Uploadable\AbstractListener;
use App\Doctrine\Extensions\Uploadable\CloudFileInfo;
use Illuminate\Filesystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;

trait UploadableFile
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @return AbstractListener
     */
    static public function listener()
    {
        throw new \LogicException('Please override listener on entity. private or public storages available.');
    }

    /**
     * @param File $file
     * @return static
     */
    static public function uploaded(File $file)
    {
        $entity = new static();
        $entity->setFile($file);
        return $entity;
    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="string")
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column(name="name", type="string")
     * @Gedmo\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(name="mime_type", type="string")
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(name="size", type="decimal")
     * @Gedmo\UploadableFileSize
     */
    private $size;

    /**
     * @ORM\PostUpdate()
     */
    public function clearLocalFile()
    {
        $this->localDisk()->delete($this->getPath());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $mimeType
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        static::listener()->addEntityFileInfo($this, new CloudFileInfo($file));
        return $this;
    }

    /**
     * Download from cloud storage and save file localy.
     *
     * @return File
     */
    public function getFile()
    {
        if ($this->file === null) {
            if (!$this->localDisk()->exists($this->getPath())) {
                $this->localDisk()->put($this->getPath(), $this->content());
            }
            $this->file = new File($this->localDisk()->path($this->getPath()));
        }
        return $this->file;
    }

    /**
     * @return string
     */
    public function url()
    {
        return static::listener()->disk()->url($this->getPath());
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function content()
    {
        return $this->listener()->disk()->get($this->getPath());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download()
    {
        return static::listener()->disk()->download($this->getPath(), $this->getName());
    }

    /**
     * @return FilesystemAdapter
     */
    protected function localDisk()
    {
        return Storage::disk('local');
    }
}
