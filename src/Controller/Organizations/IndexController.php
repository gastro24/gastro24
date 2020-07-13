<?php
namespace Gastro24\Controller\Organizations;

use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill;
use Laminas\I18n\Translator\TranslatorInterface;
use Organizations\Controller\IndexController as BaseIndexController;
use Organizations\Repository;

/**
 * @method \Acl\Controller\Plugin\Acl acl()
 * @method \Core\Controller\Plugin\PaginationParams paginationParams()
 * @method \Core\Controller\Plugin\CreatePaginator paginator(string $repositoryName, array $defaultParams = array(), bool $usePostParams = false)
 * @method \Auth\Controller\Plugin\Auth auth()
 */
class IndexController extends BaseIndexController
{
    /**
     * The organization form.
     *
     * @var \Gastro24\Form\Organizations\Organizations
     */
    private $form;

    /**
     * The organization repository.
     *
     * @var Repository\Organization
     */
    private $repository;

    /**
     * @var FormElementManagerV3Polyfill
     */
    private $formManager;

    private $viewHelper;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Create new controller instance
     *
     * @param \Gastro24\Form\Organizations\Organizations $form
     * @param Repository\Organization $repository
     * @param TranslatorInterface $translator
     * @param $formManager
     * @param $viewHelper
     */
    public function __construct(
        \Gastro24\Form\Organizations\Organizations $form,
        Repository\Organization $repository,
        TranslatorInterface $translator,
        $formManager,
        $viewHelper
    ) {
        $this->form = $form;
        $this->repository = $repository;
        $this->translator = $translator;
        $this->formManager = $formManager;
        $this->viewHelper = $viewHelper;

        parent::__construct($form, $repository, $translator, $formManager, $viewHelper);
    }

    /**
     * @inheritDoc
     */
    protected function getFormular($organization)
    {
        /* @var $container \Organizations\Form\Organizations */
        //$services  = $this->serviceLocator;
        $forms     = $this->formManager;
        $container = $forms->get(
            'Organizations/Form',
            array(
                'mode' => $organization->getId() ? 'edit' : 'new'
            )
        );
        $container->setEntity($organization);
        $container->setParam('id', $organization->getId());
//        $container->setParam('applyId',$job->applyId);

        if ('__my__' != $this->params('id', '')) {
            $container->disableForm('employeesManagement')
                ->disableForm('workflowSettings');
        } else {
            $container->disableForm('nameForm')
                ->disableForm('organizationLogo')
                ->disableForm('organizationBanner')
                ->disableForm('locationForm')
                ->disableForm('socialsForm')
                ->disableForm('descriptionForm')
                ->disableForm('workflowSettings')
                ->disableForm('profileSettings');
        }
        return $container;
    }
}