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
use Laminas\Http\PhpEnvironment\Response;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

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
        /* @var \Laminas\Http\Request $request */
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
            return $this->redirect()->toRoute('lang/job-view-extern', ['id' => $jobId, 'title' => $jobTitle])
                ->setStatusCode(301);
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
                    break;
                }

                if ($loopJob->getId() == $job->getId()) {
                    $currentJobMark = true;
                    $prevJob = (count($jobsArray) > 1) ? $jobsArray[count($jobsArray) - 2] : null;
                }
            }

            // quit loop
            if ($nextJob) {
                break;
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
        // job is intern
        if (!$job->getLink() || $jobTemplate) {
            $appTemplate = $appModel->getTemplate();
            $internModel = $this->forward()->dispatch('Jobs/Template', ['internal' => true, 'id' => $job->getId(), 'action' => 'view']);
            $internModel->setTemplate($jobTemplate ?: 'gastro24/jobs/view-intern');
            $model->addChild($internModel, 'internalJob');
            $model->setVariable('isIntern', true);
            // restore application models' template
            $appModel->setTemplate($appTemplate);
            $appModel->setVariable('job', $job);
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
            'metaTitle' => $this->buildMetaTitleByJob($job),
            'metaDescription' => $this->buildMetaDescriptionByJob($job),
            'metaOgTitle' => $this->buildMetaOgTitleByJob($job)
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
                return 'Offene Stelle als '. $job->getTitle() . 'bei ' . $orgName;
                
            }
        }
        elseif (count($locations) == 0) {
            return 'Offene Stelle als '. $job->getTitle() . ' bei ' . $orgName;
        }
        //$title = $job->getTitle() .' bei ' . $orgName . ' in ' . $city ;
        //$title = $job->getTitle() .' in ' . $city . ' bei ' . $orgName ;
        
        $title = 'Offene Stelle als '.  $job->getTitle().' in ' . $city . ' bei ' . $orgName;

        return $title;
    }

    /**
     * @param Job $job
     * @return string
     */
    private function buildMetaOgTitleByJob($job)
    {
        $locations = $job->getLocations()->toArray();
        $orgName = ($job->getOrganization()) ? $job->getOrganization()->getOrganizationName()->getName() : $job->getCompany();
        if (count($locations) > 0) {
            $location = array_shift($locations);
            $city = $location->getCity();

            if (!$city) {
                return $orgName . ' sucht: ' . $job->getTitle() . ' - Gastrojob24';
            }
        }
        elseif (count($locations) == 0) {
            return $orgName . ' sucht: ' . $job->getTitle() . ' - Gastrojob24';
        }
        $title = $orgName . ' sucht: ' . $job->getTitle() . ' in ' . $city;

        return $title . ' - Gastrojob24';
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
        $contract = $job->getClassifications()->getEmploymentTypes()->__toString() ?:'Vollzeit';

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
            //return 'Auf der Suche nach dem Traumjob? Jetzt bewerben auf die Stelle &quot;' . $title . '&quot; bei ' . $orgName . '.';
            
            return 'Auf der Suche nach dem Traumjob? ' . $orgName . ' sucht: '. $title . '. Anstellung: '. $contract . '. Jetzt bewerben!';
            
        }

        //$description = 'Auf der Suche nach dem Traumjob? Jetzt bewerben auf die Stelle &quot;' . $title . '&quot; in ' . $citiesString . ' bei ' . $orgName . '.';
        
        $description = 'Auf der Suche nach dem Traumjob? ' . $orgName . ' sucht: '. $title . ' in ' . $citiesString . '. Anstellung: '. $contract . '. Jetzt bewerben!';
        
        

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
                    'count' => 50,
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
                'd' => 10,
                'page' => $page,
            ]],
            'paginator' => [
                'as' => 'jobs',
                'Jobs/Board',
                'params' => [
                    'count' => 150,
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
