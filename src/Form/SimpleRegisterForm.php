<?php

namespace Gastro24\Form;

use Auth\Entity\User;
use Auth\Form\RegisterFormInterface;
use Auth\Form\UserPasswordFieldset;
use Auth\Options\CaptchaOptions;
use CompanyRegistration\Form\Register;
use CompanyRegistration\Options\RegistrationFormOptions;
use Core\Form\ButtonsFieldset;
use Core\Form\Form;
use Orders\Entity\InvoiceAddress;
use Orders\Entity\Order;
use Orders\Entity\SettingsContainer;
use Settings\Repository\Event\InjectSettingsEntityResolverListener;
use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Form\Fieldset;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * SimpleRegisterForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SimpleRegisterForm extends Form implements RegisterFormInterface
{
    /**
     * @var FormElementManager
     */
    protected $formManager;

    public function __construct(FormElementManager $formManager, $name = 'simple-register-form')
    {
        parent::__construct($name, []);
        $this->formManager = $formManager;

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'simple-registration-form');

        $this->add([
                'type' => 'Zend\Form\Element\Email',
                'name' => RegistrationFormOptions::FIELD_EMAIL,
                'options' => array(
                    'label' => /*@translate*/ 'E-Mail Adresse',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

//        $this->add(
//            $this->formManager->get('Auth/UserPasswordFieldset')
//        );

        $this->add([
                'type' => 'Zend\Form\Element\Password',
                'name' => 'password',
                'options' => array(
                    'label' => /* @translate */ 'Password',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type' => 'Zend\Form\Element\Password',
                'name' => 'password2',
                'options' => array(
                    'label' => /* @translate */ 'Retype password',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'firstname',
                'options'    => array(
                    'label' => /*@translate*/ 'Vorname',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'lastname',
                'label' => /*@translate*/ 'Nachname',
                'options'    => array(
                    'label' => /*@translate*/ 'Nachname',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
            'type' => 'submit',
            'name' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => /*@translate*/ 'Jetzt als Arbeitgeber registrieren',
                'class' => 'btn btn-primary registration-button'
            ),
        ]);


//        $fieldset = new Fieldset('simple-register');
//
//        $fieldset->add(
//            array(
//                'type' => 'Zend\Form\Element\Email',
//                'name' => RegistrationFormOptions::FIELD_EMAIL,
//                'options' => array(
//                    'label' => /*@translate*/ 'E-Mail Adresse',
//                    'labelWidth' => '12'
//                ),
//                'attributes' => [
//                    'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_EMAIL),
//                ],
//            )
//        );
//

//        $fieldset->add(
//            array(
//                'type'       => 'text',
//                'name'       => 'firstname',
//                'options'    => array(
//                    'label' => /*@translate*/ 'Vorname',
//                    'labelWidth' => '6'
//                ),
//                'attributes' => [
//                    'required' => true
//                ],
//            )
//        );
//
//        $fieldset->add(
//            array(
//                'type'       => 'text',
//                'name'       => 'lastname',
//                'options'    => array(
//                    'label' => /*@translate*/ 'Nachname',
//                    'labelWidth' => '6'
//                ),
//                'attributes' => [
//                    'required' => true
//                ],
//            )
//        );
//
//        $fieldset->add(
//            array(
//                'type'       => 'hidden',
//                'name'       => 'plaintext',
//                'options'    => array(
//                    'description' =>  /*@translate*/ 'Mit Absenden des Formulars erklären sie sich mit unseren ' .
//                    '<a class="link--blue" href="#">AGB</a> und der <a class="link--blue" href="#">Datenschutzerklärung</a> einverstanden.',
//                    'ignore' => true,
//                ),
//                'attributes' => [
//                    'required' => true,
//                    'class' => 'testclass'
//                ],
//                'decorators' => [
//                    ['Description', ['escape'=>false, 'tag'=>'']]
//                ]
//            )
//        );
//
//        $this->add($fieldset);

//        $mode = $options->getMode();
//        if (in_array($mode, [CaptchaOptions::RE_CAPTCHA, CaptchaOptions::IMAGE])) {
//            if ($mode == CaptchaOptions::IMAGE) {
//                $captcha = new Image($options->getImage());
//            } elseif ($mode == CaptchaOptions::RE_CAPTCHA) {
//                $captcha = new ReCaptcha($options->getReCaptcha());
//            }
//
//            if (!empty($captcha)) {
//                $this->add(
//                    array(
//                        'name'    => 'captcha',
//                        'options' => array(
//                            'label'   => /*@translate*/ 'Are you human?',
//                            'captcha' => $captcha,
//                        ),
//                        'type'    => 'Zend\Form\Element\Captcha',
//                    )
//                );
//            }
//        }

//        $buttons = new ButtonsFieldset('buttons');
//        $buttons->add(
//            array(
//                'type' => 'submit',
//                'name' => 'button',
//                'attributes' => array(
//                    'type' => 'submit',
//                    'value' => /*@translate*/ 'Jetzt als Arbeitgeber registrieren',
//                    'class' => 'btn btn-primary'
//                ),
//            )
//        );
//
//        $this->add(
//            array(
//                'name' => 'csrf',
//                'type' => 'csrf',
//                'options' => array(
//                    'csrf_options' => array(
//                        'salt' => str_replace('\\', '_', __CLASS__),
//                        'timeout' => 3600
//                    )
//                )
//            )
//        );

//        $this->add($buttons);
    }

    public function allowObjectBinding($object)
    {
        return $object instanceof User;
    }
}