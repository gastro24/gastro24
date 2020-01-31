<?php

namespace Gastro24\View\Helper;

use Core\Form\DescriptionAwareFormInterface;
use Core\Form\View\Helper\Form;
use Zend\Form\FormInterface;

/**
 * PopupForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class PopupForm extends Form
{

    /**
     * Renders a form from the provided form.
     * Wraps this form in a div-container and renders the label,
     * if any.
     *
     * @param FormInterface $form
     * @param string $layout
     * @param array $parameter
     * @uses renderBare()
     * @see \Zend\Form\View\Helper\Form::render()
     * @return string
     */
    public function render(FormInterface $form, $layout = self::LAYOUT_HORIZONTAL, $parameter = array())
    {
        /* @var $renderer \Zend\View\Renderer\PhpRenderer */
        $formContent = $this->renderBare($form, $layout, $parameter);
        $renderer    = $this->getView();

        if ($form instanceof DescriptionAwareFormInterface && $form->isDescriptionsEnabled()) {
            /* @var $form DescriptionAwareFormInterface|FormInterface */
            $renderer->headscript()->appendFile(
                $renderer->basepath('modules/Core/js/forms.descriptions.js')
            );

            if ($desc = $form->getOption('description', '')) {
                $descriptionParams=$form->getOption('description_params');
                $translator = $this->getTranslator();
                $textDomain = $this->getTranslatorTextDomain();
                $desc = $translator->translate($desc, $textDomain);
                if ($descriptionParams) {
                    array_unshift($descriptionParams, $desc);
                    $desc = call_user_func_array('sprintf', $descriptionParams);
                }
            }

            $colMd = isset($parameter['colMd']) ? $parameter['colMd'] :'8';
            $descColMd = isset($parameter['descColMd']) ? $parameter['descColMd'] : '4';

            $formContent = sprintf(
                '<div class="daf-form-container row">
                        <div class="daf-form col-md-%s"><div class="panel panel-default"><div class="panel-body">%s</div></div></div>
                        <div class="daf-desc col-md-%s">
                            <div class="daf-desc-content alert alert-info">%s</div>
                        </div>
                    </div>',
                $colMd,
                $formContent,
                $descColMd,
                $desc
            );
        } else {
            $formContent = '<div class="form-content">' . $formContent . '</div>';
        }

        $markup = '<div id="form-%s" class="form-container">'
            . '%s'
            . '%s'
            . '</div>';

        if ($label = $form->getLabel()) {
            $label = '<div class="form-headline"><h3>' . $renderer->translate($label) . '</h3></div>';
        }

        return sprintf(
            $markup,
            $form->getAttribute('id') ?: $form->getName(),
            $label,
            $formContent
        );
    }
}