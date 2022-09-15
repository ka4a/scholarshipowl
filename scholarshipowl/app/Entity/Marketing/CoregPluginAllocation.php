<?php


namespace App\Entity\Marketing;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoregPlugin
 *
 * @ORM\Table(name="coreg_plugin_allocation")
 * @ORM\Entity
 */
class CoregPluginAllocation
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count = 1;

    /**
     * @var CoregPlugin
     *
     * @ORM\OneToOne(targetEntity="CoregPlugin", fetch="EAGER")
     * @ORM\JoinColumn(name="coreg_plugin_id", referencedColumnName="coreg_plugin_id", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $coregPlugin;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|string $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return CoregPlugin
     */
    public function getCoregPlugin(): CoregPlugin
    {
        return $this->coregPlugin;
    }

    /**
     * @param CoregPlugin $coregPlugin
     */
    public function setCoregPlugin(CoregPlugin $coregPlugin)
    {
        $this->coregPlugin = $coregPlugin;
    }
}