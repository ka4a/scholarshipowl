<?php namespace App\Entity;

use App\Entity\Traits\CloudFile;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Filesystem\Filesystem;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * AccountFiles
 *
 * @ORM\Table(name="account_file")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class AccountFile
{
    use Timestamps;
    use CloudFile;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $realName;

    /**
     * @var Account
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Account", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var AccountFileCategory
     *
     * @ORM\OneToOne(targetEntity="App\Entity\AccountFileCategory", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var AccountFileType
     *
     * @ORM\OneToOne(targetEntity="App\Entity\AccountFileType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * AccountFile constructor.
     *
     * @param File    $file
     * @param Account $account
     * @param null    $fileName
     * @param null    $category
     */
    public function __construct(File $file, Account $account, $fileName = null, $category = null)
    {
        if (is_null($fileName)) {
            $fName = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : $file->getFilename();
            $fileName = uniqid($account->getAccountId()).'.'. $fName;
        }


        if(!is_null($category)){
            /**
             * we need to sub $category+1 because 1st category it's Other that was not exist in requirement_name table
             */
            $category = $category + 1;
            $category = AccountFileCategory::findOneBy(['id' => $category]) ? $category : AccountFileCategory::OTHER;
        }else{
            $category = AccountFileCategory::OTHER;
        }

        $this->setFile($file);
        $this->setAccount($account);
        $this->setFileName($fileName);
        $this->setRealName($file instanceof UploadedFile ? $file->getClientOriginalName() : $fileName);
        $this->setCategory($category);
        $this->setType(AccountFileType::findByFile($file));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return Filesystem::VISIBILITY_PRIVATE;
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        $this->updatePath();

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return AccountFile
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        $this->updatePath();

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param int|AccountFileCategory $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = AccountFileCategory::convert($category);
        $this->updatePath();

        return $this;
    }

    /**
     * @return AccountFileCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int|AccountFileType $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = AccountFileType::convert($type);

        return $this;
    }

    /**
     * @return AccountFileType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     *
     * @return AccountFile
     */
    public function setRealName(string $realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPublicUrl()
    {
        return $this->isPublic() ? route('account-file', $this->getPath()) : null;
    }

    /**
     * Should be runned to update path key
     * @return $this
     */
    protected function updatePath()
    {
        $account = $this->getAccount();
        $category = $this->getCategory();
        $fileName = $this->getFileName();

        if ($account && $category && $fileName) {
            $this->setPath(sprintf(
                '/account-files/%s/%s/%s',
                $account->getAccountId(),
                \Str::slug($category->getName()),
                $fileName
            ));
        }

        return $this;
    }

    /**
     * Returns file content
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFileContent(){
        return $this->cloud()->get($this->getPath());
    }
}

