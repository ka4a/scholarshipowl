<?php
/**
 * Created by PhpStorm.
 * User: r3volut1oner
 * Date: 3/1/16
 * Time: 4:33 PM
 */

namespace ScholarshipOwl\Data\Entity;


/**
 * Trait can be used to apply styles on entity.
 * @see ScholarshipOwl\Data\Entity\Payment\Package
 *
 * Class StylableEntity
 * @package ScholarshipOwl\Data\Entity
 */
trait StylableEntity
{

    /**
     * @var null|StyleEntity[]
     */
    protected $styles = null;

    /**
     * @param StyleEntity[] $styles
     * @return $this
     */
    public function setStyles(array $styles)
    {
        $this->styles = $styles;

        return $this;
    }

    /**
     * @param StyleEntity $style
     * @return StylableEntity
     * @throws \Exception
     */
    public function addStyle(StyleEntity $style)
    {
        $styles = $this->getStyles();
        $element = $style->getElementName();

        if (isset($styles[$element])) {
            throw new \Exception('Element entity allready exists.');
        }

        $styles[$element] = $style;

        return $this->setStyles($styles);
    }

    /**
     * @param $elementName
     * @return null|StyleEntity
     */
    public function getStyle($elementName)
    {
        $style = null;

        $styles = $this->getStyles();
        if ($elementName && isset($styles[$elementName])) {
            $style = $styles[$elementName];
        }

        return $style;
    }

    /**
     * @param $elementName
     * @return string
     */
    public function getElementCss($elementName)
    {
        $css = '';

        if ($style = $this->getStyle($elementName)) {
            $css = $style->getCSS();
        }

        return $css;
    }

    /**
     * @param $elementName
     * @return string
     */
    public function getElementContent($elementName)
    {
        $content = '';

        if ($style = $this->getStyle($elementName)) {
            $content = $style->getContent();
        }

        return $content;
    }

    /**
     * Should be implemented on entity.
     * TODO: Implement this method to work with work with provided table.
     * @return array
     */
    abstract function getStyles();

}