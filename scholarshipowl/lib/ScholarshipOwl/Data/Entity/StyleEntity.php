<?php

namespace ScholarshipOwl\Data\Entity;

class StyleEntity
{

    /**
     * @var string
     */
    protected $css;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $element;

    /**
     * @var AbstractEntity
     */
    protected $parent;

    /**
     * @param AbstractEntity $parent
     * @param string $elementName
     * @param string $css
     * @param string $content
     * @throws \Exception
     */
    public function __construct(AbstractEntity $parent, $elementName, $css = '', $content = '')
    {
        if ($elementName) {
            $this->setParentEntity($parent)
                ->setElementName($elementName)
                ->setContent($content ? $content : '')
                ->setCSS($css ? $css : '');
        } else {
            throw new \Exception("Element name required.");
        }
    }

    /**
     * @return string
     */
    public function getElementName()
    {
        return $this->element;
    }

    /**
     * @param string $element
     * @return $this
     */
    public function setElementName($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * @return AbstractEntity
     */
    public function getParentEntity()
    {
        return $this->parent;
    }

    /**
     * @param AbstractEntity $parent
     * @return $this
     */
    public function setParentEntity(AbstractEntity $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getCSS()
    {
        return $this->css;
    }

    /**
     * @param string $css
     * @return $this
     */
    public function setCSS($css = '')
    {
        $this->css = $css;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content = '')
    {
        $this->content = $content;

        return $this;
    }

}