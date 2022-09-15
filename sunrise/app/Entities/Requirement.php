<?php namespace App\Entities;

use App\Contracts\DictionaryEntityContract;
use App\Traits\DictionaryEntity;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity()
 */
class Requirement implements DictionaryEntityContract, JsonApiResource
{
    use Timestamps;
    use DictionaryEntity;

    /**
     * Known requirements
     */
    const PROOF_OF_ENROLLMENT = 'proof-of-enrollment';
    const GENERIC_PICTURE = 'generic-picture';
    const ESSAY = 'essay';
    const LINK = 'link';

    const TYPE_TEXT  = 'text';
    const TYPE_TEXT_KEY_MIN_WORDS = 'minWords';
    const TYPE_TEXT_KEY_MAX_WORDS = 'maxWords';
    const TYPE_TEXT_KEY_MIN_CHARS = 'minChars';
    const TYPE_TEXT_KEY_MAX_CHARS = 'maxChars';

    const TYPE_INPUT = 'input';
    const TYPE_INPUT_KEY_MIN_CHARS = 'minChars';
    const TYPE_INPUT_KEY_MAX_CHARS = 'maxChars';

    const TYPE_LINK  = 'link';
    const TYPE_LINK_KEY_MIN_CHARS = 'minChars';
    const TYPE_LINK_KEY_MAX_CHARS = 'maxChars';

    const TYPE_FILE  = 'file';
    const TYPE_FILE_KEY_FILE_EXTENSIONS = 'fileExtensions';
    const TYPE_FILE_KEY_MAX_FILE_SIZE = 'maxFileSize';

    const TYPE_IMAGE = 'image';
    const TYPE_IMAGE_KEY_FILE_EXTENSIONS = 'fileExtensions';
    const TYPE_IMAGE_KEY_MAX_FILE_SIZE = 'maxFileSize';
    const TYPE_IMAGE_KEY_MIN_WIDTH = 'minWidth';
    const TYPE_IMAGE_KEY_MAX_WIDTH = 'maxWidth';
    const TYPE_IMAGE_KEY_MIN_HEIGHT = 'minHeight';
    const TYPE_IMAGE_KEY_MAX_HEIGHT = 'maxHeight';

    const TYPE_VIDEO = 'video';

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'requirement';
    }

    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->id = str_slug($name);
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
