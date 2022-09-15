<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Setting
 *
 * @ORM\Table(name="setting", uniqueConstraints={@ORM\UniqueConstraint(name="uq_setting_name", columns={"name"})})
 * @ORM\Entity
 */
class Setting
{
    use Dictionary;

    const SETTING_OFFER_WALL_AFTER_APPLY = 'scholarships.offer_wall_after_apply';
    const SETTING_OFFER_WALL_AFTER_EMPTY_SELECT = 'scholarships.offer_wall_after_apply_empty';

    const TYPE_INT = "int";
    const TYPE_DECIMAL = "decimal";
    const TYPE_STRING = "string";
    const TYPE_TEXT = "text";
    const TYPE_SELECT = "select";
    const TYPE_ARRAY = "array";

    const GROUP_PAYMENT_POPUP = 'Payment Popup';
    const GROUP_MEMBERSHIPS = 'Memberships';
    const GROUP_SCHOLARSHIPS = 'Scholarships';

    const SETTING_CANCEL_SUBSCRIPTION_TEXT = 'memberships.cancel_subscription_text';
    const SETTING_FREE_TRIAL_CANCEL_SUBSCRIPTION = 'memberships.freeTrial.cancel_subscription';
    const SETTING_REDIRECT_AFTER_SUBSCRIPTION_CANCEL = 'freeTrial.redirectAfterCancel';

    const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_ALL = "show_all";
    const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_FREE = "show_free";
    const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_NONE = "show_none";

    const VALUE_SCHOLARSHIPS_VISIBILITY_PRETICK_ALL = "pretick_all";
    const VALUE_SCHOLARSHIPS_VISIBILITY_PRETICK_NONE = "pretick_none";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="setting_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=127, precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var mixed
     *
     * @ORM\Column(name="value", type="json", length=4095, precision=0, scale=0, nullable=true, unique=false)
     */
    private $value;

    /**
     * @var mixed
     *
     * @ORM\Column(name="default_value", type="json", length=4095, precision=0, scale=0, nullable=true, unique=false)
     */
    private $defaultValue;

    /**
     * @var mixed
     *
     * @ORM\Column(name="options", type="json", length=16777215, precision=0, scale=0, nullable=true, unique=false)
     */
    private $options;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", precision=0, scale=0, nullable=true, unique=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="`group`", type="string", length=127, precision=0, scale=0, nullable=true, unique=false)
     */
    private $group;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_available_in_rest", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $isAvailableInRest = 0;

    /**
     * Setting constructor.
     *
     * @param string      $type
     * @param string      $group
     * @param string      $name
     * @param string      $title
     * @param string|null $value
     * @param string|null $defaultValue
     * @param string|null $options
     */
    public function __construct(
        string $type,
        string $group,
        string $name,
        string $title,
        string $value = '',
        string $defaultValue = '',
        $options = ''
    ) {
        $this->setType($type);
        $this->setGroup($group);
        $this->setName($name);
        $this->setTitle($title);
        $this->setOptions($options);

        $this->setValue($value);
        $this->setDefaultValue($defaultValue);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Setting
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $this->validateType((string) $value, $this->getType());

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return json_decode($this->value, true);
    }

    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     *
     * @return Setting
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $this->validateType((string) $defaultValue, $this->getType());

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set options
     *
     * @param string $options
     *
     * @return Setting
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Setting
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set group
     *
     * @param string $group
     *
     * @return Setting
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return integer
     */
    public function getIsAvailableInRest()
    {
        return $this->isAvailableInRest;
    }

    /**
     * @param integer $isAvailableInRest
     */
    public function setIsAvailableInRest($isAvailableInRest)
    {
        $this->isAvailableInRest = $isAvailableInRest;
    }

    /**
     * @param string $value
     * @param string $type
     *
     * @return string
     */
	private function validateType(string $value, string $type)
    {
        switch ($type) {
            case static::TYPE_INT:
                if (!preg_match("/^\d*$/", $value)) {
                    throw new InvalidArgumentException("Setting value not an int.".$value);
                }
                break;
            case static::TYPE_DECIMAL:
                if (!preg_match("^[\d]+(|\.[\d]+)$", $value)) {
                    throw new InvalidArgumentException("Setting value not a decimal");
                }
                break;
            case static::TYPE_STRING:
                if (!is_string($value)) {
                    throw new InvalidArgumentException("Setting value not a string");
                }
                break;
            case static::TYPE_TEXT:
                if (!is_string($value)) {
                    throw new InvalidArgumentException("Setting value not a text");
                }
                break;
            case static::TYPE_ARRAY:
                if (!is_array($value)) {
                    throw new InvalidArgumentException("Setting value not an array");
                }
                break;
            case static::TYPE_SELECT:
                break;
            default:
                throw new \RuntimeException(sprintf('Unknown setting type: %s', $type));
                break;
        }

        return $value;
	}
}

