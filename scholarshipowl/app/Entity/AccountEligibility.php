<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountEligibility
 *
 * @ORM\Table(name="account_eligibility", indexes={@ORM\Index(name="account_eligibility_scholarship_id_foreign", columns={"scholarship_id"})})
 * @ORM\Entity
 */
class AccountEligibility
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=32, nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="list", type="simple_array", nullable=false)
     */
    private $list;

    /**
     * @param array $list
     *
     * @return mixed
     */
    public static function hash(array $list)
    {
        sort($list, SORT_NUMERIC);
        return md5(implode(',', array_unique($list, SORT_NUMERIC)));
    }

    /**
     * AccountEligibility constructor.
     *
     * @param string    $id
     * @param array     $list
     */
    public function __construct(string $id, array $list)
    {
        $this->setId($id);
        $this->setList($list);
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $list
     */
    public function setList(array $list)
    {
        $this->list = $list;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }
}

