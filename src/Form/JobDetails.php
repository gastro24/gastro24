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
use Core\Form\ViewPartialProviderInterface;
use Core\Form\ViewPartialProviderTrait;
use Gastro24\Filter\PdfFileUri;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobDetails extends Fieldset implements InputFilterProviderInterface, ViewPartialProviderInterface
{
    use ViewPartialProviderTrait;

    private $defaultPartial = 'gastro24/form/job-details-fieldset';

    private $gastroOptions;

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    /**
     * @param mixed $gastroOptions
     *
     * @return self
     */
    public function setGastroOptions($gastroOptions)
    {
        $this->gastroOptions = $gastroOptions;

        return $this;
    }

    public function init()
    {

        $this->add([
            'type' => 'radio',
            'name' => 'mode',
            'options' => [
                'value_options' => [
                    'uri' => 'uri',
                    'pdf' => 'pdf',
                    'html' => 'html'
                ],
                'labels' => $this->gastroOptions->getLabel('mode'),
            ],
            'attributes' => [
                'value' => 'uri',
            ],
        ]);

        $this->add([
            'type' => 'Text',
            'name' => 'uri',
            'options' => [
                'description' => 'Geben Sie die Online-Addresse des Inserats an.',
                'label' => $this->gastroOptions->getLabel('uri'),
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'placeholder' => 'https://',
                //'id' => 'csj-uri',
            ]
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'pdf',
            'options' => [
                'description' => 'Selektieren Sie hier das PDF-Dokument Ihres Jobs.',
                'label' => $this->gastroOptions->getLabel('pdf'),
                'rowClass' => 'csj-pdf-wrapper',
            ],
            'attributes' => [
                //'id' => 'csj-pdf',
            ],
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'logo',
            'options' => [
                'label' => $this->gastroOptions->getLabel('logo'),
                'description' => /* @translate */ 'Your company logo will be used as default.'
            ],
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'image',
            'options' => [
                //'label' => $this->gastroOptions->getLabel('image'),
                // WORKAROUND: options are not read in staging environment, I assume that image might be reserved word
                'label' => /* @translate */ 'Bannerbild',
                'description' => /* @translate */ 'Als Standard wird das Bannerbild aus dem Firmenprofil genutzt. Sie können es aber hier überschreiben.'
            ]
        ]);

        $this->add(
            array(
                'type' => 'infoCheckBox',
                'name' => 'hideBanner',
                'options' => array(
                    'label' => '',
                    'long_label' => 'Kein Bannerbild verwenden'
                ),
            )
        );

//        $this->add([
//            'type' => 'TextEditor',
//            'name' => 'introduction',
//            'options' => [
//                'description' => 'Geben Sie eine Einleitung zu der beworbenen Stelle ein.',
//                'label' => $this->gastroOptions->getLabel('introduction'),
//                'rowClass' => 'csj-html-input',
//                'no-submit' => true,
//            ],
//            'attributes' => [
//                'data-toggle' => 'description',
//                'data-target' => 'details-introduction',
//                'style' => 'height: auto;',
//            ],
//        ]);

        $this->add([
            'type' => 'TextEditor',
            'name' => 'position',
            'options' => [
                //'description' => 'Geben Sie die Beschreibung der beworbenen Stelle ein.',
                'label' => /*@translate*/ $this->gastroOptions->getLabel('position'),
                'rowClass' => 'csj-html-input',
                'no-submit' => true,
                'editor' => [
                    'inline' => false,
                    'paste_as_text' => true,
                    'height' => '480',
                    'language' => 'de',
                    'language_url' => '/modules/Core/js/tinymce-lang/de.js'
                ]
            ],
            'attributes' => [
                'data-toggle' => 'description',
                'data-target' => 'details-position',
            ],
        ]);

//        $this->add([
//            'type' => 'TextEditor',
//            'name' => 'requirements',
//            'options' => [
//                'description' => 'Geben Sie die Anforderungen an die Bewerber an.',
//                'label' => $this->gastroOptions->getLabel('requirements'),
//                'rowClass' => 'csj-html-input',
//                'no-submit' => true,
//            ],
//            'attributes' => [
//                'data-toggle' => 'description',
//                'data-target' => 'details-requirements',
//                'style' => 'height: auto;',
//            ],
//        ]);

//        $this->add([
//            'type' => 'TextEditor',
//            'name' => 'benefits',
//            'options' => [
//                'description' => 'Geben Sie an, was Ihre Firma zusätzlich bietet.',
//                'label' => $this->gastroOptions->getLabel('benefits'),
//                'rowClass' => 'csj-html-input',
//                'no-submit' => true,
//            ],
//            'attributes' => [
//                'data-toggle' => 'description',
//                'data-target' => 'details-benefits',
//                'style' => 'height: auto;',
//            ],
//        ]);

//        $this->add([
//            'type' => 'textarea',
//            'name' => 'description',
//            'options' => [
//                'description' => /* @translate */ 'Your company description will be used as default.',
//                'label' => $this->gastroOptions->getLabel('description'),
//                'rowClass' => 'csj-html-input',
//                'no-submit' => true,
//            ],
//            'attributes' => [
//                'data-toggle' => 'description',
//                'data-target' => 'details-description',
//                'style' => 'height: 150px;',
//            ],
//        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'companyWebsite',
            'options' => [
                'label' => /*@translate*/ 'Unternehmenswebsite',
                //'rowClass' => 'csj-uri-wrapper'
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
    }

    public function getInputFilterSpecification()
    {
        $spec = [];
        $mode = $this->get('mode')->getValue();


        if ('pdf' == $mode) {
            $spec['pdf'] = [
                'require' => !isset($_POST['pdf_uri']),
                'allow_empty' => isset($_POST['pdf_uri']),
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
            ];
        } else if ('uri' == $mode) {
            $spec['uri'] = [ 'require' => true ];
        }
        $spec += [
            //'description' => [ 'require' => true ],
            //'position'    => [ 'require' => false ],
            //'requirements' => [ 'require' => false ],
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
                        'options' => $this->gastroOptions->getLogoSize(),
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
            'image' => [
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
                        'options' => $this->gastroOptions->getImageSize(),
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
            'companyDescription' => [
                'required' => false,
                'allow_empty' => false
            ],
            'companyWebsite' => [
                'required' => false,
                'allow_empty' => true
            ],
        ];



        return $spec;
    }
}
