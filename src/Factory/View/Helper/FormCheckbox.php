<?php

namespace Gastro24\Factory\View\Helper;

use Zend\Form\Element\Checkbox as CheckboxElement;
use Zend\Form\ElementInterface;

/**
 * FormCheckbox.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class FormCheckbox extends \Zend\Form\View\Helper\FormCheckbox
{
    /** @var bool  */
    protected $labelAfterBox = false;

    /**
     * Render a form <input> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (! $element instanceof CheckboxElement) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Zend\Form\Element\Checkbox',
                __METHOD__
            ));
        }

        $name = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes            = $element->getAttributes();
        $attributes['name']    = $name;
        $attributes['type']    = $this->getInputType();
        $attributes['value']   = $element->getCheckedValue();
        $closingBracket        = $this->getInlineClosingBracket();

        if ($element->isChecked()) {
            $attributes['checked'] = 'checked';
        }

        $rendered = sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $closingBracket
        );

        if ($element->getOption('afterText')) {
            $rendered .= '<span>' . $element->getOption('afterText') . '</span>';
        }

        if ($element->useHiddenElement()) {
            $hiddenAttributes = [
                'disabled' => isset($attributes['disabled']) ? $attributes['disabled'] : false,
                'name'     => $attributes['name'],
                'value'    => $element->getUncheckedValue(),
            ];

            $rendered = sprintf(
                    '<input type="hidden" %s%s',
                    $this->createAttributesString($hiddenAttributes),
                    $closingBracket
                ) . $rendered;
        }

        return $rendered;
    }

    /**
     * @return bool
     */
    public function isLabelAfterBox()
    {
        return $this->labelAfterBox;
    }

    /**
     * @param bool $labelAfterBox
     */
    public function setLabelAfterBox($labelAfterBox)
    {
        $this->labelAfterBox = $labelAfterBox;
    }
}