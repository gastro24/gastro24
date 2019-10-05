<?php

namespace Gastro24\View\Helper;

use Core\Form\View\Helper\SearchForm as BaseSearchFormHelper;
use Core\Form\ViewPartialProviderInterface;
use Zend\Form\Element\Hidden;
use Zend\Form\FormInterface;

/**
 * SearchForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SearchForm extends BaseSearchFormHelper
{
    public function render(FormInterface $form, $colMap=null, $buttonsSpan = null)
    {
        $headscript = $this->getView()->plugin('headscript');
        $basepath   = $this->getView()->plugin('basepath');

        $headscript
            ->appendFile($basepath('modules/Core/js/core.forms.js'))
            ->appendFile($basepath('modules/Core/js/core.searchform.js'));

        if (is_int($colMap)) {
            $buttonsSpan = $colMap;
            $colMap = null;
        }

        $form->prepare();

        if ($form instanceof ViewPartialProviderInterface) {
            return $this->getView()->partial($form->getViewPartial(), [ 'element' => $form, 'colMap' => $colMap, 'buttonsSpan' => $buttonsSpan ]);
        }

        $content = $this->renderElements($form, $colMap, $buttonsSpan);

        return $this->openTag($form)
            . '<div class="row" style="padding: 0 15px;">'
            . $content . '</div>' . $this->closeTag();
    }

    public function renderElements($form, $colMap = null, $buttonsSpan = null)
    {
        if ($form instanceof ViewPartialProviderInterface) {
            return $this->getView()->partial($form->getViewPartial(), [ 'element' => $form, 'colMap' => $colMap, 'buttonsSpan' => $buttonsSpan ]);
        }

        if (null === $colMap) {
            $colMap = $form->getColumnMap();
        }

        $formElement = $this->getView()->plugin('formElement');
        $content = '';
        $buttonsRendered = false;
        $i=0;
        foreach ($form as $element) {
            if ($element instanceof Hidden) {
                $content .= $formElement($element);
                continue;
            }

            if (isset($colMap[$element->getName()])) {
                $cols = $colMap[$element->getName()];
            } elseif (isset($colMap[$i])) {
                $cols = $colMap[$i];
            } else {
                $cols = $element->getOption('span') ?: 12;
            }

            if ($element->getName() == $form->getButtonElement()) {
                $content.='<div class="input-group col-md-' . $cols . ' col-xs-12">'
                    . $formElement($element)
                    . '<div class="input-group-btn search-form-buttons" style="width: 1px;">'
                    . $this->renderButtons($form->getButtons()) . '</div>'
                    . '</div>';
                $buttonsRendered = true;
            } else {
                $content .= '<div class="input-group col-md-' . $cols . ' col-xs-12">'
                    . $formElement($element)
                    . '</div>';
            }

            $i += 1;
        }

        if (!$buttonsRendered) {
            if (null === $buttonsSpan) {
                $buttonsSpan = $form->getOption('buttons_span') ?: 12;
            }
            $content .= '<div class="input-group search-form-buttons col-md-' . $buttonsSpan . ' col-xs-12 text-right">'
                . '<div class="btn-group">' . $this->renderButtons($form->getButtons()) .'</div></div>';
        }

        return $content;
    }

}