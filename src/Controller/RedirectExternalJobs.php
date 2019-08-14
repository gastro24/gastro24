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
use Jobs\Entity\Job;
use Jobs\Entity\Status;
use Zend\Http\PhpEnvironment\Response;
use Zend\I18n\Translator\TranslatorInterface;
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
     * @var TranslatorInterface
     */
    private $translator;

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
        TranslatorInterface $translator,
        $mainPath
    )
    {
        $this->validator = $validator;
        $this->templatesMap = $templatesMap;
        $this->solrClient = $solrClient;
        $this->translator = $translator;
        $this->mainPath = $mainPath;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        /* @var Response $response */
        $response = $this->getResponse();
        $container = new Container('gastro24_jobboardcontainer');

        // direct call of job, clear session container
        if (!$request->getHeaders()->get('referer') ||
            (strpos($request->getHeaders()->get('referer'), 'sitemap.xml') !== false) ||
            (strpos($request->getHeaders()->get('referer'), rtrim($this->mainPath, '/')) === false)) {
            $container = $this->clearJobboardContainer($container);
        }

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

        $jobTitle = $this->params()->fromRoute('title');
        if (strpos($jobTitle, 'job-') === 0 && $job->getStatus()->getName() != Status::EXPIRED ) {
            $jobTitle = substr($jobTitle, 4);
            $jobId = $this->params()->fromRoute('id');
            return $this->redirect()->toRoute('lang/job-view-extern', ['id' => $jobId, 'title' => $jobTitle]);
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

        $result = $this->pagination([
            'params' => ['Jobs_Board', [
                'q',
                'count' => 10,
                'page' => 1,
                'l',
                'd' => 10]
            ],
            'form' => ['as' => 'filterForm', 'Jobs/JobboardSearch'],
            'paginator' => ['as' => 'jobs', 'Jobs/Board']
        ]);

        $model->setVariables($result);
        $model->setVariables([
            'prevJob' => $prevJob,
            'nextJob' => $nextJob,
            'metaTitle' => $this->buildMetaTitleByJob($job),
            'metaDescription' => $this->buildMetaDescriptionByJob($job),
        ]);
        $model->setTemplate('gastro24/jobs/view-extern');

        if ($this->params()->fromRoute('isPreview')) {
            $appModel->setVariable('noHeader', true);
            $appModel->setVariable('noFooter', true);
        }

        return $model;
    }

    /**
     * @param Job $job
     * @return string
     */
    private function buildMetaTitleByJob($job)
    {
        $locations = $job->getLocations()->toArray();
        $orgName = ($job->getOrganization()) ? $job->getOrganization()->getOrganizationName()->getName() : $job->getCompany();
        $date = $job->getDatePublishStart() ?: $job->getDateCreated();
        $dateString = $this->translator->translate($date->format('F')) . ' ' . $date->format('Y');
        if (count($locations) > 0) {
            $location = array_shift($locations);
            $city = $location->getCity();

            if (!$city) {
                return $job->getTitle() . '- Job bei ' . $orgName;
            }
        }
        elseif (count($locations) == 0) {
            return $job->getTitle() . ' - Job bei ' . $orgName;
        }

      //  $title = $job->getTitle() . ' in ' . $city .' - ' . $dateString;
      
      
       $title = $job->getTitle() . ' - Job in ' . $city .' bei ' . $orgName;
       
      

        return $title;
    }

    /**
     * @param Job $job
     * @return string
     */
    private function buildMetaDescriptionByJob($job)
    {
        $title = trim($job->getTitle(), '"');
        $orgName = ($job->getOrganization()) ? $job->getOrganization()->getOrganizationName()->getName() : $job->getCompany();
        $locations = $job->getLocations()->toArray();

        if (count($locations) > 0) {
            $cities = [];
            foreach ($locations as $loc) {
                if ($loc->getCity()) {
                    $cities[] = $loc->getCity();
                }
            }
            $citiesString = implode(',', $cities);

        }
        elseif (count($locations) == 0) {
            return 'Auf der Suche nach dem Traumjob? Jetzt bewerben auf die Stelle &quot;' . $title . '&quot; bei ' . $orgName . '.';
        }

        $description = 'Auf der Suche nach dem Traumjob? Jetzt bewerben auf die Stelle &quot;' . $title . '&quot; in ' . $citiesString . ' bei ' . $orgName . '.';

        return $description;
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

    private function clearJobboardContainer($container)
    {
        // clear values in session container
        $container->exchangeArray([]);

        $jobsBoardContainer = new Container('Jobs_Board');
        $jobsBoardContainer->params['q'] = null;

        return $container;
    }
}
