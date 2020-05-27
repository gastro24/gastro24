<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Form;

use Core\Entity\Hydrator\EntityHydrator;
use Core\Form\Element\InfoCheckbox;
use Core\Form\Form;
use Core\Form\SummaryForm;
use Core\Form\ViewPartialProviderInterface;
use Core\Form\ViewPartialProviderTrait;
use Gastro24\Filter\PdfFileUri;
use Jobs\Entity\Category;
use Jobs\Entity\Location;
use Orders\Form\InvoiceAddressFieldset;
use Zend\Form\Fieldset;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\ArrayUtils;

class InvoiceAddressForm extends Form
{
    public function __construct($name = 'single-job-address-form')
    {
        parent::__construct($name, []);

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'single-job-address-form');
    }

    public function init()
    {
        $this->add([
            'type' => InvoiceAddressFieldset::class,
            'options' => [
                'input_filter_spec' => [
                    'gender' => [
                        'required' => false,
                        'allow_empty' => true
                    ],
                    'phonenumber' => [
                        'filters' => [
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            ['name' => 'regex', 'options' => [
                                'pattern' => '~^[0-9 /\+]*$~',
                                'message' => 'Es sind nur Ziffern, "/" und "+" erlaubt',
                                'translatorTextDomain' => 'Gastro24',
                            ]],
                        ],
                    ],
                ],
            ]
        ]);

        $invoiceAddressFieldset = $this->get('invoiceAddress');
        $this->add([
                'type'       => 'text',
                'name'       => 'firstname',
                'options'    => array(
                    'label' => /*@translate*/ 'Vorname',
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
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );
        $this->add([
            'type' => 'radio',
            'name' => 'gender',
            'options' => [
                'label'         => /*@translate */ 'Salutation',
                'value_options' => [
                    'male'   => /*@translate */ 'Mr.',
                    'female' => /*@translate */ 'Mrs.',
                ]
            ],
            'attributes' => [
                'required' => true,
                'value' => 'male',
                'class' => 'form-control'
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'phonenumber',
            'options' => [
                'label' => 'Telefon',
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);

        $invoiceAddressFieldset->remove('gender');
        $invoiceAddressFieldset->get('company')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('invoiceAddress[company]');

        $invoiceAddressFieldset->get('street')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('invoiceAddress[street]');

        $invoiceAddressFieldset->get('zipCode')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('invoiceAddress[zipCode]');

        $invoiceAddressFieldset->get('city')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true);

        $invoiceAddressFieldset->get('region')
            ->setAttribute('class', 'form-control')
            ->setName('invoiceAddress[region]');

        $invoiceAddressFieldset->get('email')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('invoiceAddress[email]');

        $this->add(
            array(
                'type' => InfoCheckbox::class,
                'name' => 'termsAccepted',
                'options' => array(
                    'long_label' => /*@translate*/ 'Ich erkläre mich mit den %s (AGB) von Gastrojob24 einverstanden',
                    'linktext' => /*@translate*/ 'terms an conditions',
                    'route' => 'lang/wordpress',
                    'params' => [
                        'type' => 'page',
                        'id'   => 'agb',
                    ],
                ),
                'attributes' => array(
                    'class' => 'terms-checkbox',
                    'required' => true,
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Button',
                //'type' => 'Core/Spinner-Submit',
                'name' => 'submit',
                'options' => array(
                    'label' => /*@translate*/ 'Weiter &raquo; ',
                ),
                'attributes' => array(
                    'id' => 'buttons-submit',
                    'type' => 'submit',
                    'value' => 'Weiter',
                    'class' => 'cam-btn-save',
                    'style' => 'float:right'
                ),
            )
        );

        $this->add([
            'name' => 'otherAddress',
            'type' => InvoiceAddressFieldset::class,
        ]);
        /** @var InvoiceAddressFieldset $otherAddressFieldset */
        $otherAddressFieldset = $this->get('otherAddress');
        $this->add([
            'type'       => 'text',
            'name'       => 'firstname-other-address',
            'options'    => array(
                'label' => /*@translate*/ 'Vorname',
            ),
            'attributes' => [
                'required' => true,
                'class' => 'form-control'
            ],
        ]);
        $this->add([
            'type'       => 'text',
            'name'       => 'lastname-other-address',
            'options'    => array(
                'label' => /*@translate*/ 'Name',
            ),
            'attributes' => [
                'required' => true,
                'class' => 'form-control'
            ],
        ]);
        $otherAddressFieldset->remove('gender');
        $otherAddressFieldset->remove('company');
        $otherAddressFieldset->remove('email');

        $otherAddressFieldset->get('street')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('otherAddress[street]');

        $otherAddressFieldset->get('zipCode')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('otherAddress[zipCode]');

        $otherAddressFieldset->get('city')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true)
            ->setName('otherAddress[city]');

        $otherAddressFieldset->get('region')
            ->setAttribute('class', 'form-control')
            ->setName('otherAddress[region]');

        $this->add([
            'type' => 'radio',
            'name' => 'gender-other-address',
            'options' => [
                'label'         => /*@translate */ 'Salutation',
                'value_options' => [
                    'male'   => /*@translate */ 'Mr.',
                    'female' => /*@translate */ 'Mrs.',
                ]
            ],
            'attributes' => [
                'required' => true,
                'value' => 'male',
                'class' => 'form-control'
            ],
        ]);
        $this->add(
            array(
                'type' => InfoCheckbox::class,
                'name' => 'toggleOtherAddress',
                'options' => array(
                    'long_label' => /*@translate*/ 'Abweichende Rechnungsadresse',
                ),
                'attributes' => array(
                    'class' => 'toggle-address-checkbox',
                ),
            )
        );
    }

    public function isValid()
    {
        // Workaround: otherwise setData sets hasValidation to false again
        return \Zend\Form\Form::isValid();
    }

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }

        return $this->hydrator;
    }

    public function getInputFilterSpecification()
    {
        return [
            'phonenumber' => [
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'regex', 'options' => [
                        'pattern' => '~^[0-9 /\+]*$~',
                        'message' => 'Es sind nur Ziffern, "/" und "+" erlaubt',
                        'translatorTextDomain' => 'Gastro24',
                    ]],
                ],
            ],
        ];
    }
}
