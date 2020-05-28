<?php

namespace Gastro24\Form\View\Helper;

use \Core\Form\View\Helper\FormDatePicker as BaseFormDatePicker;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormText;

/**
 * FormDatePicker.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class FormDatePicker extends BaseFormDatePicker
{

    public function render(ElementInterface $element = null)
    {
        /* @var \Zend\View\Renderer\PhpRenderer $view */
        $view = $this->getView();
        /* @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->plugin('headscript');
        /* @var \Zend\View\Helper\BasePath $basePath */

        $basePath = $view->plugin('basePath');
        $params   = $view->plugin('params'); /* @var \Core\View\Helper\Params $params */
        $lang     = $params('lang');

        //$headScript->appendFile($basePath('/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js'));
        //if (in_array($this->language, ['de'])) {
        //    $headScript->appendFile($basePath('assets/bootstrap-datepicker/locales/bootstrap-datepicker.de.min.js'));
        //}

//        $headScript->offsetSetScript(
//            'datepicker_' . $element->getAttribute('id'),
//            '
//            $(document).ready(function() {
//                $(".datepicker-group").on("click", function() {
//                    console.log("clicked")
//                    $(this).parent().find(":input").datepicker({
//                        defaultDate: "+1w",
//                        changeMonth: true,
//                        minDate: 0
//                    })
//                });
//            });'
//        );

        $dataDateFormat = $element->getAttribute('data-date-format') ?? 'yyyy-mm-dd';

        $element->setAttributes([
            'data-date-language' => $lang,
            'data-provide' => 'datepicker',
            'data-date-format' => $dataDateFormat
        ]);
        $input = FormText::render($element);

        /*
         *
         */
        $markup = '<div class="input-group date">%s<div data-toggle="description" data-target="%s" class="input-group-addon datepicker-group" onClick="$(this).parent().find(\':input\').datepicker({startDate: \'now\'}, \'show\')">' .
            '<i class="fa fa-calendar"></i></div></div><div class="checkbox"></div>';

        $markup = sprintf(
            $markup,
            $input,
            $element->getAttribute('id')
        );

        return $markup;
    }
}