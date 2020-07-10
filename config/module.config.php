<?php
namespace Gastro24;

use Gastro24\Controller\ForgotPasswordPopupController;
use Gastro24\Filter\OrganizationJobsListQuery;
use Gastro24\Form\JobDetailsHydrator;
use Gastro24\Form\JobDetailsHydratorFactory;
use Gastro24\Form\JobPreviewFieldsetDelegator;
use Gastro24\Form\OrdersSettingsFieldset;
use Gastro24\Options\Landingpages;
use Jobs\Listener\Events\JobEvent;
use SimpleImport\Entity\Crawler;
use Laminas\ServiceManager\Factory\InvokableFactory;

Module::$isLoaded = true;

/**
 * create a config/autoload/Gastro24.global.php and put modifications there
 */

return [

    'doctrine' => [
        'driver' => [
            'odm_default' => [
                'drivers' => [
                    'Gastro24\Entity' => 'annotation',
                ],
            ],
            'annotation' => [
                /*
                 * All drivers (except DriverChain) require paths to work on. You
                 * may set this value as a string (for a single path) or an array
                 * for multiple paths.
                 * example https://github.com/doctrine/DoctrineORMModule
                 */
                'paths' => [ __DIR__ . '/../src/Entity'],
            ],
        ],

        'eventmanager' => [
            'odm_default' => [
                'subscribers' => [
                    Repository\Events\InjectJobSnapshotHydratorSubscriber::class,
                    Listener\JobDeletedListener::class
                ],
            ],
        ],
    ],

    'Gastro24' => [
        'dashboard' => [
            'enabled' => true,
            'widgets' => [
                'productInfo' => [
                    'script' => 'gastro24/dashboard',
                ],
            ],
        ],
    ],
    'Jobs' => [
        'dashboard' => [
            'enabled' => true,
            'widgets' => [
                'recentJobs' => [
                    'controller' => Controller\JobController::class
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'Gastro24\Form\Filter\SimpleRegisterInputFilter' => 'Gastro24\Form\Filter\SimpleRegisterInputFilter',
            'Gastro24\Form\Filter\SingleJobFormInputFilter' => 'Gastro24\Form\Filter\SingleJobFormInputFilter',
            'Gastro24\Form\Filter\CompanyRegisterInputFilter' => 'Gastro24\Form\Filter\CompanyRegisterInputFilter',
        ],
        'factories' => [
            'Auth/Dependency/Manager' => 'Gastro24\Factory\Dependency\ManagerFactory',
            'Gastro24\Service\Register' => 'Gastro24\Factory\Service\RegisterFactory',
            'Gastro24\Service\RegisterConfirmation' => 'Gastro24\Factory\Service\RegisterConfirmationFactory',
            WordpressApi\Service\WordpressClient::class => WordpressApi\Factory\Service\WordpressClientFactory::class,
            WordpressApi\Listener\WordpressContentSnippet::class => WordpressApi\Factory\Listener\WordpressContentSnippetFactory::class,
            Listener\UserRegisteredListener::class => Listener\UserRegisteredListenerFactory::class,
            Listener\VoidListener::class => InvokableFactory::class,
            Listener\CreateJobOrder::class => Listener\CreateJobOrderFactory::class,
            Listener\SingleJobAcceptedListener::class => Listener\SingleJobAcceptedListenerFactory::class,
            Listener\JobFileUpload::class => Listener\JobFileUploadFactory::class,
            Listener\DeleteTemplateImage::class => Listener\DeleteTemplateImageFactory::class,
            Listener\DeleteBannerImageReference::class => \Gastro24\Factory\Listener\DeleteBannerImageReferenceFactory::class,
            Listener\AutoApproveChangedJobs::class => Listener\AutoApproveChangedJobsFactory::class,
            Listener\ExpiredJobListener::class => Listener\ExpiredJobListenerFactory::class,
            Listener\AutomaticJobApproval::class => Listener\AutomaticJobApprovalFactory::class,
            'Gastro24\Validator\IframeEmbeddableUri' => InvokableFactory::class,
            Listener\DeleteJob::class => Listener\DeleteJobFactory::class,
            Listener\GoogleIndexApi::class => Listener\GoogleIndexApiFactory::class,
            Listener\SettingsChangedListener::class => \Gastro24\Factory\Listener\SettingsChangedListenerFactory::class,
            Listener\JobDeletedListener::class => [Listener\JobDeletedListener::class,'factory'],
            'Gastro24\Form\ForgotPasswordPopup' => \Gastro24\Factory\Form\ForgotPasswordPopupFactory::class,
            'Auth/Adapter/UserLogin' => Factory\Service\UserAdapterFactory::class,
        ],
        'aliases' => [
            'Orders\Form\Listener\InjectInvoiceAddressInJobContainer' => Listener\VoidListener::class,
            'Orders\Form\Listener\ValidateJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders\Form\Listener\DisableJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders/Listener/BindInvoiceAddressEntity' => Listener\VoidListener::class,
            'Orders/Listener/CreateJobOrder' => Listener\CreateJobOrder::class,
            \Jobs\Listener\DeleteJob::class => Listener\DeleteJob::class,

        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\WordpressPageController::class => Factory\Controller\WordpressPageControllerFactory::class,
            Controller\RedirectExternalJobs::class => Controller\RedirectExternalJobsFactory::class,
            Controller\CreateSingleJobController::class => Factory\Controller\CreateSingleJobFactory::class,
            Controller\LpStellenanzeigen::class => InvokableFactory::class,
            'Auth\Controller\Register' => Factory\Controller\RegisterControllerFactory::class,
            Controller\RegisterConfirmationController::class => Factory\Controller\RegisterConfirmationControllerFactory::class,
            Controller\SuggestJobs::class => Factory\Controller\SuggestJobFactory::class,
            Controller\OrdersController::class => Factory\Controller\OrdersControllerFactory::class,
            'Gastro24/Jobs/Console' => [Controller\Console\JobsConsoleController::class,'factory'],
            'Gastro24/Jobs/Console/DeleteJobs' => [Controller\Console\DeleteJobsController::class,'factory'],
            'Gastro24/Jobs/Console/GoogleIndex' => [Controller\Console\GoogleIndexController::class,'factory'],
            'Core/File'    => Factory\Controller\FileControllerFactory::class,
            Controller\JobController::class => [Controller\JobController::class,'factory'],
            Controller\ForgotPasswordPopupController::class => Factory\Controller\ForgotPasswordPopupControllerFactory::class,
            'Organizations/Index' => Factory\Controller\OrganizationsIndexControllerFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CreateSingleJob::class => Factory\Controller\Plugin\CreateSingleJobFactory::class,
        ],
    ],

    'filters' => [
        'factories' => [
            WordpressApi\Filter\PageIdMap::class => Factory\Filter\WpApiPageIdMapFactory::class,
            Filter\PdfFileUri::class => Filter\PdfFileUriFactory::class,
            Filter\OrganizationJobsListQuery::class => InvokableFactory::class,
            'Gastro24/Paginator/JobsSimilarPaginationQuery' => 'Gastro24\Paginator\JobsSimilarPaginationQueryFactory',
            Filter\JobBoardPaginationQuery::class => Factory\Filter\JobBoardPaginationQueryFactory::class,
        ],
        'aliases' => [
            'Organizations/ListJobQuery' => Filter\OrganizationJobsListQuery::class,
            'Solr/Jobs/PaginationQuery' => Filter\JobBoardPaginationQuery::class,
        ]
    ],

    'validators' => [
        'factories' => [
            Validator\IframeEmbeddableUri::class => InvokableFactory::class,
        ],
    ],

    'hydrators' => [
        'factories' => [
            JobDetailsHydrator::class => JobDetailsHydratorFactory::class,
            \Gastro24\Form\Organizations\Hydrator\OrganizationDescriptionHydrator::class => \Gastro24\Factory\Form\Hydrator\OrganizationDescriptionHydratorFactory::class,
            \Gastro24\Form\Organizations\Hydrator\OrganizationSocialHydrator::class => \Gastro24\Factory\Form\Hydrator\OrganizationSocialHydratorFactory::class,
            \Gastro24\Entity\Hydrator\OrderHydrator::class => \Gastro24\Entity\Hydrator\OrderHydratorFactory::class,
            'Organizations/Hydrator/Banner' => \Gastro24\Factory\Form\Hydrator\OrganizationBannerHydratorFactory::class,
        ],
    ],

    'simple_import_crawler_processor_manager' => [
        'factories' => [
            Crawler::TYPE_JOB => SimpleImport\JobProcessorFactory::class
        ]
    ],

    'view_helpers' => [
        'invokables' => [
            'formcheckbox' => \Gastro24\Factory\View\Helper\FormCheckbox::class,
            'searchForm' => \Gastro24\View\Helper\SearchForm::class,
            'formDatePicker' => \Gastro24\Form\View\Helper\FormDatePicker::class,
        ],
        'factories' => [
            WordpressApi\View\Helper\WordpressContent::class => WordpressApi\Factory\View\Helper\WordpressContentFactory::class,
            View\Helper\LandingpagesList::class => Factory\View\Helper\LandingpagesListFactory::class,
            View\Helper\JobboardApplyUrl::class => Factory\View\Helper\JobboardApplyUrlFactory::class,
            View\Helper\LogoUri::class => View\Helper\LogoUriFactory::class,
            View\Helper\OrgProfileUrl::class => InvokableFactory::class,
            View\Helper\IsCrawlerJob::class => InvokableFactory::class,
            View\Helper\IsEmbeddable::class => View\Helper\IsEmbeddableFactory::class,
            View\Helper\JobTemplate::class => View\Helper\JobTemplateFactory::class,
            View\Helper\JobCount::class => View\Helper\JobCountFactory::class,
            View\Helper\SimilarJobs::class => View\Helper\SimilarJobsFactory::class,
            View\Helper\SimilarCompanyJobs::class => View\Helper\SimilarCompanyJobsFactory::class,
            View\Helper\HasAutomaticJobActivation::class => View\Helper\HasAutomaticJobActivationFactory::class,
            View\Helper\ShowAutomaticJobActivationHint::class => View\Helper\ShowAutomaticJobActivationHintFactory::class,
            View\Helper\HydrateOrderObject::class => View\Helper\HydrateOrderObjectFactory::class,
            View\Helper\JsonLd::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            View\Helper\PublishDateFormatter::class => View\Helper\PublishDateFormatterFactory::class,
            View\Helper\PopupForm::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            View\Helper\JobDraftsCount::class => View\Helper\JobDraftsCountFactory::class,
        ],
        'aliases' => [
            'wordpress' => WordpressApi\View\Helper\WordpressContent::class,
            'landingpages' => View\Helper\LandingpagesList::class,
            'gastroApplyUrl' => View\Helper\JobboardApplyUrl::class,
            'gastroLogoUri' => View\Helper\LogoUri::class,
            'orgProfileUrl' => View\Helper\OrgProfileUrl::class,
            'gastroIsEmbeddable' => View\Helper\IsEmbeddable::class,
            'gastroJobTemplate' => View\Helper\JobTemplate::class,
            'gastroJobCount' => View\Helper\JobCount::class,
            'hasAutomaticJobActivation' => View\Helper\HasAutomaticJobActivation::class,
            'showAutomaticJobActivationHint' => View\Helper\ShowAutomaticJobActivationHint::class,
            'hydrateOrderObject' => View\Helper\HydrateOrderObject::class,
            'similarJobs' => View\Helper\SimilarJobs::class,
            'similarCompanyJobs' => View\Helper\SimilarCompanyJobs::class,
            'jsonLd' => View\Helper\JsonLd::class,
            'isCrawlerJob' => View\Helper\IsCrawlerJob::class,
            'publishDateFormatter' => View\Helper\PublishDateFormatter::class,
            'popupForm' => View\Helper\PopupForm::class,
            'gastroJobDraftsCount' => View\Helper\JobDraftsCount::class,
        ],
        'delegators' => [
            'jobUrl' => [
                Factory\View\Helper\JobUrlDelegatorFactory::class,
            ],
        ],
    ],

    'view_helper_config' => [
        'asset' => [
            'resource_map' => json_decode(file_get_contents(__DIR__ . '/../gastro24-grunt-cache-bust.json'), true),
        ],
        'headscript' => [
            'lang/jobs/manage' => [
                [\Laminas\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("#sf-general-portalForm").hide(); }); })(jQuery);'],
            ],
            [\Laminas\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("#jobs-list-filter").find("button[type=\'reset\']").text("X"); }); })(jQuery);'],
            'lang/applications/detail' => [
                [\Laminas\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("button[data-target=\'#cam-move-application\']").hide(); }); })(jQuery);'],
            ],
        ],
    ],

    'view_manager' => [
         'template_map' => [
             'error/404' => __DIR__ . '/../view/error/404.phtml',
             'error/403' => __DIR__ . '/../view/error/403.phtml',
             'error/index' => __DIR__ . '/../view/error/index.phtml',
             'layout/layout' => __DIR__ . '/../view/layout.phtml',
             'core/index/index' => __DIR__ . '/../view/index.phtml',
             'applications/apply/index' => __DIR__ . '/../view/applications/apply/index.phtml',
             'applications/detail/pdf' => __DIR__ . '/../view/applications/manage/detail.pdf.phtml',
             'piwik' => __DIR__ . '/../view/piwik.phtml',
             'footer' => __DIR__ . '/../view/footer.phtml',
             'footer-application' => __DIR__ . '/../view/footer-application.phtml',
             'jobs/jobboard/index.ajax.phtml' => __DIR__ . '/../view/jobs/index.ajax.phtml',
             'jobs/jobboard/index' => __DIR__ . '/../view/jobs/index.phtml',
             'jobs/manage/approval' => __DIR__ . '/../view/jobs/approval.phtml',
             'jobs/form/preview' => __DIR__ . '/../view/jobs/form/preview.phtml',
             'jobs/index/index.ajax.phtml' => __DIR__ . '/../view/jobs/index/index.ajax.phtml',
             'main-navigation' => __DIR__ . '/../view/main-navigation.phtml',
             'auth/index/login-info' => __DIR__ . '/../view/login-info.phtml',
             'gastro24/wordpress-page/index' => __DIR__ . '/../view/gastro24/wordpress-page/index.phtml',
             'gastro24/wordpress-page/index.ajax' => __DIR__ . '/../view/gastro24/wordpress-page/index.ajax.phtml',
             'content/regionen' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'content/staedte' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'jobs-by-mail/form/subscribe/form' => __DIR__ . '/../view/jobs-by-mail/form.phtml',
             'jobs-by-mail/mail/jobs' => __DIR__ . '/../view/mail/jobs.phtml',
             'jobs-by-mail/mail/confirmation' => __DIR__ . '/../view/mail/confirmation.phtml',
             'jobs-by-mail/mail/confirmation.en' => __DIR__ . '/../view/mail/confirmation.en.phtml',
             'mail/header' => __DIR__ . '/../view/mail/header.phtml',
             'mail/footer' => __DIR__ . '/../view/mail/footer.phtml',
             'mail/footer.en' => __DIR__ . '/../view/mail/footer.en.phtml',
             'mail/forgotPassword' =>  __DIR__ . '/../view/mail/forgot-password.phtml',
             'mail/forgotPassword.en' =>  __DIR__ . '/../view/mail/forgot-password.en.phtml',
             'mail/register' =>  __DIR__ . '/../view/mail/register.phtml',
             'mail/register.en' =>  __DIR__ . '/../view/mail/register.en.phtml',
             'mail/job-accepted.en' => __DIR__ . '/../view/mail/job-accepted.en.phtml',
             'mail/job-accepted' => __DIR__ . '/../view/mail/job-accepted.phtml',
             'mail/job-created.en' => __DIR__ . '/../view/mail/job-created.en.phtml',
             'mail/job-created' => __DIR__ . '/../view/mail/job-created.phtml',
             'mail/job-pending.en' => __DIR__ . '/../view/mail/job-pending.en.phtml',
             'mail/job-pending' => __DIR__ . '/../view/mail/job-pending.phtml',
             'mail/job-rejected.en' => __DIR__ . '/../view/mail/job-rejected.en.phtml',
             'mail/job-rejected' => __DIR__ . '/../view/mail/job-rejected.phtml',
             'mail/job-expired.en' => __DIR__ . '/../view/mail/job-expired.en.phtml',
             'mail/job-expired' => __DIR__ . '/../view/mail/job-expired.phtml',
             'gastro24/lp-stellenanzeigen/index' => __DIR__ . '/../view/gastro24/lp/stellenanzeigen-schalten.phtml',
             'gastro24/mail/single-job-created' => __DIR__ . '/../view/mail/single-job-created.phtml',
             'gastro24/mail/single-job-pending' => __DIR__ . '/../view/mail/single-job-pending.phtml',
             'gastro24/mail/single-job-accepted' => __DIR__ . '/../view/mail/single-job-accepted.phtml',
             'gastro24/mail/abo-created' => __DIR__ . '/../view/mail/abo-created.phtml',
             'gastro24/mail/invoice-address-changed' => __DIR__ . '/../view/mail/invoice-address-changed.phtml',
             'auth/mail/new-registration.en' => __DIR__ . '/../view/mail/new-registration.en.phtml',
             'auth/mail/new-registration' => __DIR__ . '/../view/mail/new-registration.phtml',
             'auth/mail/user-confirmed.en' => __DIR__ . '/../view/mail/user-confirmed.en.phtml',
             'auth/mail/user-confirmed' => __DIR__ . '/../view/mail/user-confirmed.phtml',
             'startpage'  => __DIR__ . '/../view/startpage.phtml',
             'templates/default/index' => __DIR__ . '/../view/templates/default/index.phtml',
             'templates/classic/index' => __DIR__ . '/../view/templates/classic/index.phtml',
             'templates/modern/index' => __DIR__ . '/../view/templates/modern/index.phtml',
             'iframe/iFrame.phtml' => __DIR__ . '/../view/jobs/iframe/iFrame.phtml',
             'gastro24/jobs/view-extern' => __DIR__ . '/../view/jobs/view-extern.phtml',
             'gastro24/jobs/view-mcdonalds' => __DIR__ . '/../view/jobs/view-mcdonalds.phtml',
             'gastro24/jobs/view-zfv' => __DIR__ . '/../view/jobs/view-zfv.phtml',
             'gastro24/jobs/view-intern' => __DIR__ . '/../view/jobs/view-intern.phtml',
             'gastro24/job/dashboard' => __DIR__ . '/../view/jobs/index/dashboard.phtml',
             'gastro24/create-single-job/index' => __DIR__ . '/../view/jobs/create-single-job.phtml',
             'gastro24/create-single-job/payment' => __DIR__ . '/../view/jobs/create-single-job-payment.phtml',
             'gastro24/create-single-job/success' => __DIR__ . '/../view/jobs/create-single-job-success.phtml',
             'gastro24/create-single-job/failed' => __DIR__ . '/../view/jobs/create-single-job-failed.phtml',
             'gastro24/form/create-single-job' => __DIR__ . '/../view/jobs/create-single-job-form.phtml',
             'gastro24/form/job-details-fieldset' => __DIR__ . '/../view/jobs/job-details-fieldset.phtml',
             'gastro24/dashboard' => __DIR__ . '/../view/gastro24/dashboard.phtml',
             'gastro24/job/change-status' => __DIR__ . '/../view/jobs/admin/edit.phtml',
             'core/index/dashboard' => __DIR__ . '/../view/gastro24/main-dashboard.phtml',
             'gastro24/list/index' => __DIR__ . '/../view/orders/list/index.phtml',
             'layout/application-form' => __DIR__ . '/../view/layout-application-form.phtml',
             'contactform.view' => __DIR__ . '/../view/contactform.phtml',
             'gastro24/jobs/user-product-info' => __DIR__ . '/../view/jobs/user-product-info.phtml',
             'pagination-control' => __DIR__ . '/../view/pagination-control.phtml',
             'auth/index/index' => __DIR__ . '/../view/auth/index/index.phtml',
             'auth/password/index' => __DIR__ . '/../view/auth/password/index.phtml',
             'auth/forgot-password/index' => __DIR__ . '/../view/auth/forgot-password/index.phtml',
             'content/applications-privacy-policy' => __DIR__ . '/../view/application-disclaimer.phtml',
             'orders/list/index' => __DIR__ . '/../view/gastro24/orders/index.phtml',
             'orders/list/index.ajax' => __DIR__ . '/../view/gastro24/orders/index.ajax.phtml',
             'orders/view/index' => __DIR__ . '/../view/gastro24/orders/modal.phtml',
             'organizations/profile/index' => __DIR__ . '/../view/organizations/profile/index.phtml',
             'organizations/profile/index.ajax' => __DIR__ . '/../view/organizations/profile/index.ajax-detail.phtml',
             'organizations/profile/detail' => __DIR__ . '/../view/organizations/profile-detail.phtml',
             'organizations/profile/detail.ajax' => __DIR__ . '/../view/organizations/profile-detail.ajax.phtml',
             'organizations/profile/disabled' => __DIR__ . '/../view/organizations/profile-disabled.phtml',
             'organizations/index/edit' => __DIR__ . '/../view/organizations/index/form.phtml',
             'organizations/mail/invite-employee.phtml' => __DIR__ . '/../view/mail/invite-employee.phtml',
             'settings/index/index' => __DIR__ . '/../view/settings/index.phtml',
         ],

        'template_path_stack' => array(
            'Registration' => __DIR__ . '/../view',
        ),
    ],

    'translator'   => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
                'text_domain' => \Gastro24\Module::TEXT_DOMAIN,
            ],
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s-override.php',
            ],
        ],
    ],

    'form_elements' => [
        'invokables' => [
            'Jobs/Description' => 'Gastro24\Form\JobsDescription',
            'Jobs/PreviewFieldset' => Form\JobPreviewFieldsetDelegator::class,
            'Applications/Attributes' => Form\Applications\Attributes::class, // only needed until YAWIK fixed error
            'Auth/Login' => 'Gastro24\Form\Login',
            'Jobs/ClassificationsFieldset'  => 'Gastro24\Form\ClassificationsFieldset',
            'Organizations/Form' => 'Gastro24\Form\Organizations\Organizations',
            'Organizations/OrganizationsContactFieldset' => 'Gastro24\Form\Organizations\OrganizationsContactFieldset',
            'Gastro24/Organizations/OrganizationsSocialForm' => 'Gastro24\Form\Organizations\OrganizationsSocialForm',
        ],
        'factories' => [
            Form\SingleJobForm::class => \Gastro24\Factory\Form\SingleJobFormFactory::class,
            Form\InvoiceAddressForm::class => \Gastro24\Factory\Form\InvoiceAddressFormFactory::class,
            Form\UserProductInfo::class => InvokableFactory::class,
            Form\InvoiceAddressSettingsFieldset::class => \Settings\Form\Factory\SettingsFieldsetFactory::class,
            Form\OrdersSettingsFieldset::class => \Settings\Form\Factory\SettingsFieldsetFactory::class,
            Form\JobDetails::class => Form\JobDetailsFactory::class,
            Form\JobDetailsForm::class => InvokableFactory::class,
            Form\RegisterCompanyForm::class => \Gastro24\Factory\Form\RegisterCompanyFactory::class,
            'Gastro24/JobPdfUpload' => Form\JobPdfFactory::class,
            'Auth\Form\SimpleRegister' => \Gastro24\Factory\Form\SimpleRegisterFactory::class,
            'Jobs/JobboardSearch' => \Gastro24\Factory\Form\JobboardSearchFactory::class,
            'LocationSelect' => \Gastro24\Factory\Form\GeoSelectFactory::class,
            'Gastro24\Form\Login' => \Gastro24\Factory\Form\LoginFactory::class,
            'Gastro24\Form\ForgotPasswordPopup' => \Gastro24\Factory\Form\ForgotPasswordPopupFactory::class,
            'Gastro24\Form\Organizations\OrganizationsDescriptionFieldset' => \Gastro24\Factory\Form\OrganizationsDescriptionFieldsetFactory::class,
            'Gastro24\Form\Organizations\OrganizationsSocialFieldset' => \Gastro24\Factory\Form\OrganizationsSocialFieldsetFactory::class,
            'Organizations/Banner' => \Gastro24\Factory\Form\OrganizationBannerImageFactory::class,
        ],
        'aliases' => [
            'Orders/InvoiceAddressSettingsFieldset' => Form\InvoiceAddressSettingsFieldset::class,
            'Orders/SettingsFieldset' => Form\OrdersSettingsFieldset::class,
        ]
    ],

    'form_elements_config' => [
        'file_upload_factories' => [
            'organization_banner_image' => [
                'hydrator' => 'Organizations/Hydrator/Banner',
            ],
        ],
    ],

    'mails' => [
        'factories' => [
            'Gastro24/SingleJobMail' => Mail\SingleJobMailFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'stellenanzeigen-schalten' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/lg/stellenanzeigen-schalten',
                    'defaults' => [
                        'controller' => Controller\LpStellenanzeigen::class,
                        'action' => 'index'
                    ],
                    'may_terminate' => true
                ]
            ],
            'lang' => [
                'child_routes' => [
                    'facebook' => [
                        'type' => 'regex',
                        'options' => [
                            'regex' => '/(fbclid|gclid)=(?<shareParam>[a-zA-Z0-9_\-]+)',
                            'spec' => '/(fbclid|gclid)=%shareParam%',
                            'defaults' => [
                                'controller' => 'Core/Index',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'wordpress' => [
                        'type' => 'regex',
                        'options' => [
                            'regex' => '/(?<id>[a-zA-Z0-9_\-]+)\.html',
                            'spec' => '/%id%.html',
                            'defaults' => [
                                'controller' => Controller\WordpressPageController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'forgot-password-startpage' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/forgot-password',
                            'defaults' => [
                                'controller' => Controller\ForgotPasswordPopupController::class,
                                'action' => 'index'
                            ],
                            'may_terminate' => true
                        ]
                    ],
                    'register-organization-confirmation' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/auth/register-organization-confirmation/:userId',
                            'defaults' => [
                                'controller' => Controller\RegisterConfirmationController::class,
                                'action' => 'organization'
                            ]
                        ],
                        'may_terminate' => true
                    ],
                    'saved-jobs' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/saved-jobs',
                            'defaults' => [
                                'controller' => Controller\JobController::class,
                                'action' => 'savedJobs',
                            ],
                        ],
                    ],
                    'job-view-extern' => [
                        'type' => 'regex',
                        'options' => [
                            'regex' => '/(?<title>.*?)-(?<id>[a-f0-9]+)\.html',
                            'spec' => '/jobs/%title%-%id%.html',
                            //'route' => '/jobs/',
                            'defaults' => [
                                'controller' => Controller\RedirectExternalJobs::class,
                                'action' => 'index',
                            ],
                        ],
//                        'priority' => 8000,
                    ],
                    'jobs' => [
                        'options' => [
                            'route' => '/job',
                        ],
                        'child_routes' => [
                            'view' => [
                                //'type' => 'regex',
                                'options' => [
                                  //  'regex' => '-(?<id>[a-f0-9]+)\.html',
                                  //  'spec' => '-%id%.html',
                                  //  'route' => null,
                                    'defaults' => [
                                        'controller' => Controller\RedirectExternalJobs::class,
                                        'action' => 'index',
                                        'isPreview' => true,
                                    ],
                                ],
                            ],
                            'single' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/single',
                                    'defaults' => [
                                        'controller' => Controller\CreateSingleJobController::class,
                                        'action' => 'index',
                                    ],
                                    'may_terminate' => true,
                                ],
                            ],
                            'single-payment' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/single-payment[/:show]',
                                    'defaults' => [
                                        'controller' => Controller\CreateSingleJobController::class,
                                        'action' => 'payment',
                                    ],
                                    'may_terminate' => true,
                                ],
                            ],
                            'single-success' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/single-success',
                                    'defaults' => [
                                        'controller' => Controller\CreateSingleJobController::class,
                                        'action' => 'success',
                                    ],
                                    'may_terminate' => true,
                                ],
                            ],
                            'single-failed' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/single-failed',
                                    'defaults' => [
                                        'controller' => Controller\CreateSingleJobController::class,
                                        'action' => 'failed',
                                    ],
                                    'may_terminate' => true,
                                ],
                            ],
                            'suggest-job' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/suggest',
                                    'defaults' => [
                                        'controller' => Controller\SuggestJobs::class,
                                        'action' => 'index'
                                    ]
                                ],
                            ],
                            'change-status' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/status',
                                    'defaults' => [
                                        'controller' => Controller\JobController::class,
                                        'action' => 'changeStatus'
                                    ]
                                ],
                            ],
                            'save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:id/save',
                                    'defaults' => [
                                        'controller' => Controller\JobController::class,
                                        'action' => 'saveJob',
                                    ]
                                ],
                            ]
                        ],

                    ],
                    'jobboard' => [
                        'options' => [
                            'route' => '/jobs',
                        ],
                    ],
                    'organizations-profiles' => [
                                'type' => 'Regex',
                                'options' => [
                                    'regex' => '/profile-(?<name>.*?)-(?<id>[a-f0-9]+)$',
                                    'spec' => '/profile-%name%-%id%',
                                    'route' => '/',
                                    'constraints' => [
                                        'id' => '\w+',
                                    ],
                                    'defaults' => [
                                        'action' => 'detail',
                                        'controller' => 'Organizations/Profile'
                                    ],
                                ],
                    ],

                    'order-job-activation' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/orders/:id/jobactivation',
                            'defaults' => [
                                'controller' => Controller\OrdersController::class,
                                'action' => 'jobactivation',
                            ],
                            'constraints' => [
                                'id' => '\w+',
                            ]
                        ],
                    ],

                    'orders-list' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/orders',
                            'defaults' => [
                                'controller' => Controller\OrdersController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],

        ],
    ],

    'paginator_manager' => [
        'factories' => [
            // replace Jobs/Board paginator with this paginator
            'Gastro24/Jobs/Similar' => 'Gastro24\Paginator\JobsSimilarPaginatorFactory',
            'Solr/Jobs/Board'   => 'Gastro24\Paginator\JobsBoardPaginatorFactory',
        ]
    ],

    'options' => [

        'Gastro24/WordpressApiOptions' => [
            'class' => WordpressApi\Options\WordpressApiOptions::class,
        ],

        Options\JobsearchQueries::class => [[
            'config' => [
                'Kategorien' => [
                    'Küche' => [
                        'Chefkoch' => 'q=Chefkoch+OR+Küchenchef',
                        'Koch/Köchin' => 'q=Koch'
                    ],
                    'Marketing' => [
                        'Business Manager' => 'q=Business+Manager',
                    ],
                ],
                'Regionen' => [
                    'Deutschland' => [
                        'Süd-Deutschland' => ',,region_MultiString=Hessen,Baden-Württemberg,Bayern',
                    ],
                ],
                'Städte' => [
                    'Deutschland' => [
                        'Süd-Deutschland' => ',,region_MultiString=Hessen,Baden-Württemberg,Bayern',
                    ],
                ],
            ]],
        ],
        Landingpages::class => [],
        Options\JobDetailsForm::class => [],
        Options\CompanyTemplatesMap::class => [[
            /* Firmen-Id => View-Template-Name */
            'map' => [
                // McDonald's Suisse Restaurants
                '5bcf612fb6428b0b1008db60' => 'gastro24/jobs/view-mcdonalds',
                // zfv
                '5aa7e1d77bb2b57b5d3c03e4' => 'gastro24/jobs/view-zfv',
                 // sv group
                 '59e4b53e7bb2b553412f9be9' => 'gastro24/jobs/view-zfv',
                // buergenstock
                '5bcdfda2b6428ba17c5c9048' => 'gastro24/jobs/view-zfv',
                // adecco
                '5a0809397bb2b582267c7a97' => 'gastro24/jobs/view-zfv',
                //compass
                '5d8da3cb3c050f4563073b32' => 'gastro24/jobs/view-zfv',
                //active gastro
                '5d8e267b3c050f7ff12f75d2' => 'gastro24/jobs/view-zfv',
                //randstad
                '5a970fea7bb2b5a578812d52' => 'gastro24/jobs/view-zfv',
                //gastronet.ch
                '5db7f6bb3c050f1326103fc2' => 'gastro24/jobs/view-zfv',
            ],
        ]],
    ],

    'event_manager' => [

        'Core/ViewSnippets/Events' => [ 'listeners' => [
            WordpressApi\Listener\WordpressContentSnippet::class => ['wordpress-page', true],
        ]],

        'Auth/Events' => [ 'listeners' => [
            Listener\UserRegisteredListener::class => [ \Auth\Listener\Events\AuthEvent::EVENT_USER_REGISTERED, true ],
        ]],

        'Jobs/JobContainer/Events' => [ 'listeners' => [
            Listener\ValidateUserProduct::class => [ 'ValidateJob', true ],
            //Listener\InjectUserProductInfo::class => [ \Core\Form\Event\FormEvent::EVENT_INIT, true ],
        ]],

        'Jobs/Events' => [ 'listeners' => [
            Listener\GoogleIndexApi::class => [
                'events' => [
                    JobEvent::EVENT_JOB_CREATED,
                    JobEvent::EVENT_STATUS_CHANGED => 'onUpdate',
                    JobEvent::EVENT_JOB_ACCEPTED => 'onAdminApproved',
                ],
                'lazy' => true
            ],
            Listener\IncreaseJobCount::class => [ JobEvent::EVENT_JOB_CREATED, true ],
            Listener\SingleJobAcceptedListener::class => [ JobEvent::EVENT_JOB_ACCEPTED, true ],
            Listener\AutoApproveChangedJobs::class => [JobEvent::EVENT_STATUS_CHANGED, true],
            Listener\ExpiredJobListener::class => [JobEvent::EVENT_STATUS_CHANGED, true],
            Listener\UpdateJobCount::class => [JobEvent::EVENT_STATUS_CHANGED, true],
            Listener\AutomaticJobApproval::class => [JobEvent::EVENT_JOB_CREATED, true],
        ]],

        'Core/Ajax/Events' => [ 'listeners' => [
            Listener\DeleteJob::class => ['jobs.delete', true],
            Listener\JobFileUpload::class  => [
                'events' => ['jobdetailsupload', 'jobdetailsdelete' => 'deletePdfFile'],
                'lazy'   => true
            ],
        ]],

        'Core/File/Events' => [ 'listeners' => [
            Listener\DeleteTemplateImage::class => [ \Core\Listener\Events\FileEvent::EVENT_DELETE, true ],
            Listener\DeleteBannerImageReference::class => [ \Core\Listener\Events\FileEvent::EVENT_DELETE, true ]
        ]],
    ],

    'listeners' => [
        Listener\SettingsChangedListener::class
    ],
];
