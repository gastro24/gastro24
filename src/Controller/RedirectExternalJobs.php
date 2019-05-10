<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Core\Entity\Exception\NotFoundException;
use Gastro24\Options\CompanyTemplatesMap;
use Gastro24\Session\VisitedJobsContainer;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test
 */
class RedirectExternalJobs extends AbstractActionController
{
    /**
     * @var \Gastro24\Validator\IframeEmbeddableUri
     */
    private $validator;

    /**
     * @var CompanyTemplatesMap
     */
    private $templatesMap;

    private $solrClient;
    private $mainPath;

    public function __construct(
        \Gastro24\Validator\IframeEmbeddableUri $validator,
        CompanyTemplatesMap $templatesMap,
        $solrClient,
        $mainPath
    )
    {
        $this->validator = $validator;
        $this->templatesMap = $templatesMap;
        $this->solrClient = $solrClient;
        $this->mainPath = $mainPath;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        /* @var Response $response */
        $response = $this->getResponse();

        $container = new Container('gastro24_jobboardcontainer');

        try {
            /* @var \Jobs\Entity\JobInterface $job */
            $job = $this->initializeJob()->get($this->params());
        } catch (NotFoundException $e) {
            /* @var Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_404);
            return [
                'message' => 'Kein Job gefunden'
            ];
        }

        // get prev and next job
        $jobsArray = [];
        $currentJobMark = false; //benchmark if looped over current job
        $page = 1;

        if ($container->fromCompanyProfile && $container->companyName) {
            $paginator = $this->getCompanyJobsPaginator($page, $container->companyId);
        }
        else {
            $paginator = $this->getJobPaginator($page);

        }

        $counter = 0;
        $max = $paginator->getTotalItemCount();
        $prevJob = null;
        $nextJob = null;

        while ($counter < $max) {
            foreach ($paginator as $loopJob) {
                // add job - only internal
                $loopJobTemplate = $this->templatesMap->getTemplate($loopJob->getOrganization());
                $isIntern = (!$loopJob->getLink() || $loopJobTemplate);
                $isEmbeddable = $this->validator->isValid($loopJob->getLink());
                $jobHasExternLink = (!$isIntern && !$isEmbeddable);
                $counter++;

                if (!$jobHasExternLink) {
                    $jobsArray[] = $loopJob;
                }
                else {
                    continue;
                }

                if ($currentJobMark) {
                    $nextJob = $loopJob;
                    // quit loop
                    $counter = $max;
                    break;
                }

                if ($loopJob->getId() == $job->getId()) {
                    $currentJobMark = true;
                    $prevJob = (count($jobsArray) > 1) ? $jobsArray[count($jobsArray) - 2] : null;
                }
            }

            $page++;
            // organization search uses mongo adapter -> here pagination needs to be recreated, otherwise error
            if ($paginator->getAdapter() instanceof \Core\Paginator\Adapter\DoctrineMongoLateCursor) {
                $paginator = $this->getCompanyJobsPaginator($page, $container->companyId);
            }
            else {
                $paginator->setCurrentPageNumber($page);
            }
        }

        $appModel = $this->getEvent()->getViewModel();
        $model = new ViewModel(['job' => $job]);
        $jobTemplate = $this->templatesMap->getTemplate($job->getOrganization());
        if (!$job->getLink() || $jobTemplate) {
            $appTemplate = $appModel->getTemplate();
            $internModel = $this->forward()->dispatch('Jobs/Template', ['internal' => true, 'id' => $job->getId(), 'action' => 'view']);
            $internModel->setTemplate($jobTemplate ?: 'gastro24/jobs/view-intern');
            $model->addChild($internModel, 'internalJob');
            $model->setVariable('isIntern', true);
            // restore application models' template
            $appModel->setTemplate($appTemplate);
        } else {
            $visitedJobsContainer = new VisitedJobsContainer();
            $isVisited            = $this->params()->fromRoute('isPreview') ? false : $visitedJobsContainer->isVisited($job);
            $this->validator->setBasePath($this->mainPath);
            $isEmbeddable         = $this->validator->isValid($job->getLink());

            if (!$isVisited && !$isEmbeddable) {
                $response->getHeaders()->addHeaderLine('Refresh', '4;' . $job->getLink());
                $visitedJobsContainer->add($job);
            }
            $model->setVariables([
                'isVisited' => $isVisited,
                'isEmbeddable' => $isEmbeddable,
            ]);

        }
        $model->setVariables([
            'prevJob' => $prevJob,
            'nextJob' => $nextJob,
        ]);
        $model->setTemplate('gastro24/jobs/view-extern');

        if ($this->params()->fromRoute('isPreview')) {
            $appModel->setVariable('noHeader', true);
            $appModel->setVariable('noFooter', true);
        }

        return $model;
    }

    private function getCompanyJobsPaginator($page, $companyId)
    {
        $result = $this->pagination([
            'params' => ['Organization_Jobs',[
                'q',
                'organization_id' => $companyId
            ],
            ],
            'paginator' => [
                'as' => 'jobs',
                'Organizations/ListJob',
                'params' => [
                    'count' => 4,
                    'page' => $page,
                ]
            ],
        ]);

        return $result['jobs'];
    }

    private function getJobPaginator($page)
    {
        $result = $this->pagination([
            'params' => ['Jobs_Board', [
                'q',
                'l',
                'd' => 10
            ]],
            'paginator' => [
                'as' => 'jobs',
                'Jobs/Board',
                'params' => [
                    'count' => 4,
                    'page' => $page,
                ]
            ]
        ]);

        return $result['jobs'];
    }
}
