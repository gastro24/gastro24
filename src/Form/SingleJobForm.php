<?php

namespace Gastro24\Form;

use Core\Entity\Hydrator\EntityHydrator;
use Core\Form\Form;
use Gastro24\Filter\PdfFileUri;
use Jobs\Entity\Category;
use Jobs\Entity\Location;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;
use Laminas\InputFilter\InputFilterProviderInterface;

class SingleJobForm extends Form implements InputFilterProviderInterface
{
    private $employmentTypesHydrator;

    /**
     * @var FormElementManager
     */
    protected $formManager;

    /**
     * @var \Gastro24\Options\JobDetailsForm
     */
    private $gastroOptions;

    public function __construct(FormElementManager $formManager, $gastroOptions, $name = 'single-job-form')
    {
        parent::__construct($name, []);
        $this->formManager = $formManager;
        $this->gastroOptions = $gastroOptions;

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'single-job-form file-upload');
        $this->setAttribute('data-dropzone', 'csj-pdf-wrapper');
    }

    public function init()
    {
        $this->add([
            'type' => 'text',
            'name' => 'jobTitle',
            'options' => [
                'label' => /*@translate*/ 'Stellentitel',
                'labelWidth' => '12'
            ],
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'locationStreet_1',
            'options' => [
                'label' => /*@translate*/ 'street',
            ],
            'attributes' => [
                'required' => true,
                'id' => 'locationStreet_1',
                'class' => 'form-control',
            ]
        ]);

        $this->add([
            'type' => 'LocationSelect',
            'name' => 'location_1',
            'options' => [
                'label' => 'Ort',
                'location_entity' => Location::class
            ],
            'attributes' => [
                'required' => true,
                'multiple' => false,
                'data-placeholder' => '',
                'data-width' => '100%',
                'id' => 'csj-location-1',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'locationZipCode_1',
            'options' => [
                'label' => /*@translate*/ 'PLZ',
            ],
            'attributes' => [
                'class' => 'form-control',
            ]
        ]);

        $types = $this->formManager->get(
            'Core/Tree/Select',
            [
                'tree' => [
                    'entity' => Category::class,
                    'value' => 'employmentTypes',
                ],
                'name' => 'employmentTypes',
                'options' => [
                    'label' => /*@translate*/ 'Employment Types',
                    'labelWidth' => '12',
                    'description' => /*@translate*/ 'Manage the employment types you want to assign to jobs.',
                ],
                'attributes' => [
                    'required' => true,
                    'data-width' => '100%',
                    'multiple' => true,
                ]
            ]
        );

        $this->add($types);
        $hydrator = $this->getEmploymentTypesHydrator();
        $hydrator->addStrategy('employmentTypes', $types->getHydratorStrategy());

        $this->add([
            'type' => 'TextEditor',
            'name' => 'position',
            'options' => [
                'label' => /*@translate*/ 'Stellenbeschreibung',
                'rowClass' => 'csj-html-input',
                'no-submit' => true,
                'editor' => [
                    'inline' => false,
                    'paste_as_text' => true,
                    'language' => 'de',
                    'language_url' => '/modules/Core/js/tinymce-lang/de.js'
                ]
            ],
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'pdf',
            'options' => [
                'label' => /*@translate*/ 'PDF Datei',
                'rowClass' => 'csj-pdf-wrapper',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'pdf',
            ],
        ]);

        $this->add([
            'type' => 'Email',
            'name' => 'applicationEmail',
            'options' => [
                'label' => /*@translate*/ 'E-Mail Adresse für Bewerbungen',
            ],
            'attributes' => [
                'class' => 'form-control',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'applicationUri',
            'options' => [
                'label' => /*@translate*/ 'Direktlink zum Bewerberformular',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'placeholder' => 'https://',
                'class' => 'form-control',
            ]
        ]);



        $this->add([
            'type'       => 'text',
            'name'       => 'company',
            'options'    => [
                'label'       => /*@translate*/ 'Company',
            ],
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
            ]
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'logo',
            'options' => [
                'label' => $this->gastroOptions->getLabel('logo'),
                'enable_ajax' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'logo',
            ]
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'bannerImage',
            'options' => [
                'label' => $this->gastroOptions->getLabel('image'),
                'enable_ajax' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'bannerImage',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'companyWebsite',
            'options' => [
                'label' => /*@translate*/ 'Unternehmenswebsite',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'placeholder' => 'https://',
                'class' => 'form-control',
            ]
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'companyDescription',
            'options' => [
                'label' => $this->gastroOptions->getLabel('description'),
            ],
            'attributes' => [
                'class' => 'form-control',
                'style' => 'display: block; width: 100%;height: 150px;',
            ],
        ]);

        $this->add([
            'name'       => 'publishDate',
            'type'       => 'Core/Datepicker',
            'options'    => [
                'label'           => /*@translate*/ 'Earliest starting date',
                'description'     => /*@translate*/ 'Enter the earliest starting date.',
                'disable_capable' => [
                    'description' => /*@translate*/ 'Ask the applicant about the earliest starting date.',
                ],
                'format' => 'd/m/Y',
                'minDate' => 0
            ],
            'attributes' => [
                'data-date-format' => 'dd/m/yyyy',
                'data-language' => 'de',
                'data-date-start-date' => date('d/n/Y'),
                'class' => 'form-control',
                'id' => 'datepicker-publishDate'
            ]
        ]);

        $this->add(
            [
                'type'       => 'hidden',
                'name'       => 'totalPrice',
            ]
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
    }

    public function getInputFilterSpecification()
    {
        $spec = [
            'jobTitle' => [
                'required' => true,
            ],
            'location_1' => [
                'required' => true,
            ],
            'employmentTypes' => [
                'required' => true,
            ],
            'applicationUri' => [
                'required' => false,
                'allow_empty' => true
            ],
            'applicationEmail' => [
                'required' => false,
                'allow_empty' => true
            ],
            'companyDescription' => [
                'required' => false,
                'allow_empty' => false
            ],
            'companyWebsite' => [
                'required' => false,
                'allow_empty' => true
            ],
            'publishDate' => [
                'required' => false,
                'allow_empty' => true
            ],
            'pdf' => [
                'required' => false,
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => 'FileMimeType',
                        'options' => [
                            'mimeType' => 'application/pdf',
                            'disableMagicFile' => true,
                            'magicFile' => false,
                        ]
                    ],
                    [
                        'name' => 'FileExtension',
                        'options' => [
                            'extension' => 'pdf',
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => 'FileRenameUpload',
                        'options' => [
                            'target' =>  'public/static/Jobs/job.pdf',
                            'randomize' => true,
                        ],
                    ],
                    [
                        'name' => PdfFileUri::class
                    ],
                ],
            ],
            'logo' => [
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => 'FileMimeType',
                        'options' => [
                            'mimeType' => 'image',
                            'disableMagicFile' => true,
                            'magicFile' => false,
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => \Core\Filter\File\Resize::class,
                        'options' => [
                            'max-width' => 250,
                        ],
                    ],
                    [
                        'name' => \Core\Filter\File\Entity::class,
                        'options' => [
                            'file_entity' => \Gastro24\Entity\TemplateImage::class,
                            'repository' => true,
                        ],
                    ],
                ],
            ],
            'bannerImage' => [
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => 'FileMimeType',
                        'options' => [
                            'mimeType' => 'image',
                            'disableMagicFile' => true,
                            'magicFile' => false,
                        ],
                    ],
                ],
                'filters' => [
                    [
                        'name' => \Core\Filter\File\Resize::class,
                        'options' => [
                            'max-width' => 1200,
                            'max-height' => 410
                        ],
                    ],
                    [
                        'name' => \Core\Filter\File\Entity::class,
                        'options' => [
                            'file_entity' => \Gastro24\Entity\TemplateImage::class,
                            'repository' => true,
                        ],
                    ],
                ],
            ],
        ];



        return $spec;
    }

    public function isValid()
    {
        // Workaround: otherwise setData sets hasValidation to false again
        return \Laminas\Form\Form::isValid();
    }

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }

        return $this->hydrator;
    }

    public function getEmploymentTypesHydrator()
    {
        if (!$this->employmentTypesHydrator) {
            $this->employmentTypesHydrator = new EntityHydrator();
        }

        return $this->employmentTypesHydrator;
    }

}
