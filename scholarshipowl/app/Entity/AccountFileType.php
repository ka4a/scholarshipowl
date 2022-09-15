<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * AccountFileType
 *
 * @ORM\Table(name="account_file_type")
 * @ORM\Entity
 */
class AccountFileType
{
    use Dictionary;

    const UNKNOWN = 1;
    const TEXT = 2;
    const IMAGE = 3;
    const TABLES = 4;
    const VIDEO = 5;

    /**
     * @var array
     */
    static private $typeByMime = [
        '/^text\/.*$/'               => self::TEXT,
        '/^application\/msword$/'    => self::TEXT,
        '/^application\/pdf$/'       => self::TEXT,
        '/^image\/.*$/'              => self::IMAGE,
        '/^application\/.*excel.*$/' => self::TABLES,
        '/^video\/.*$/'              => self::VIDEO,
    ];

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @param \SplFileInfo $file
     *
     * @return AccountFileType
     */
    public static function findByFile(\SplFileInfo $file) : self
    {
        $type = static::UNKNOWN;
        $mime = @mime_content_type($file->getRealPath());

        foreach (static::$typeByMime as $regexp => $checkType) {
            if (preg_match($regexp, $mime)) {
                $type = $checkType;
                break;
            }
        }

        return static::find($type);
    }
}

