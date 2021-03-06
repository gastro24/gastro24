<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Gastro24\Listener\GoogleIndexApi;
use Gastro24\Options\ConsoleDeleteJobs;
use Interop\Container\ContainerInterface;
use Jobs\Entity\StatusInterface;
use Jobs\Listener\Events\JobEvent;
use MongoDB\BSON\ObjectId;
use Laminas\Log\LoggerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ProgressBar\Adapter\Console as ConsoleAdapter;
use Laminas\ProgressBar\ProgressBar;

/**
 * GoogleIndexController.php
 *
 * Console Controller which checks for new inserted crawler jobs
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class GoogleIndexController extends AbstractActionController
{
    /**
     * @var RepositoryService
     */
    private $repositories;

    /**
     * @var ConsoleDeleteJobs
     */
    private $options;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Gastro24\View\Helper\JobTemplate
     */
    private $jobTemplateHelper;

    private $jobUrlHelper;

    private $configPath;

    public function __construct(
        RepositoryService $repositories,
        ConsoleDeleteJobs $options,
        $logger,
        $jobTemplateHelper,
        $jobUrlHelper,
        $configPath
    ) {
        $this->repositories = $repositories;
        $this->options = $options;
        $this->logger = $logger;
        $this->jobTemplateHelper = $jobTemplateHelper;
        $this->jobUrlHelper = $jobUrlHelper;
        $this->configPath = $configPath;
    }

    public static function factory(ContainerInterface $container)
    {
        $helpers   = $container->get('ViewHelperManager');
        return new self(
            $container->get('repositories'),
            $container->get(\Gastro24\Options\ConsoleDeleteJobs::class),
            $container->get('Core/Log'),
            $helpers->get('gastroJobTemplate'),
            $helpers->get('jobUrl'),
            __DIR__.'/../../../test/sandbox/config/autoload/'
        );
    }

    public function googleIndexJobsAction()
    {
        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo = $this->repositories->get('Jobs/Job');

        $toDate = new \DateTime('today midnight');
        $fromDate = new \DateTime('yesterday midnight');

        $crawlerJobsQuery = $this->getCrawlerJobsQuery($fromDate, $toDate);
        $jobs = $jobsRepo->findBy($crawlerJobsQuery);

        // foreach jobs - check if org id has template
        foreach ($jobs as $job) {
            $hasJobTemplate = $this->jobTemplateHelper->__invoke($job->getOrganization());

            if ($hasJobTemplate) {
                $jobUrl = $this->jobUrlHelper->__invoke(
                    $job,
                    [
                        'linkOnly' => true,
                        'absolute' => true,
                    ],
                    [
                        'lang' => 'de'
                    ]
                );

                //HINT: workaround to get base url
                $jobUrl = str_replace('http://', 'https://www.gastrojob24.ch', $jobUrl);

                // DEV Mode
                if (!file_exists($this->configPath . GoogleIndexApi::AUTH_FILE)) {
                    $this->logger->info('DEV MODE: GOOGLE INDEX API: Crawler Cron - Job created. Send ' .
                        GoogleIndexApi::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
                    return;
                }
                else {
                    $this->logger->info('GOOGLE INDEX API: Crawler Cron - Job created. Send ' .
                        GoogleIndexApi::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
                    $response = $this->sendGoogleRequest($jobUrl);
                }
            }
        }

        // single jobs with publish date in future
        $singleJobsQuery = $this->getSingleJobsQuery($fromDate, $toDate);
        $singleJobs = $jobsRepo->findBy($singleJobsQuery);
        foreach ($singleJobs as $job) {
            if ($this->jobHasRedirectOrLink($job)) {
                return;
            }

            $jobUrl = $this->jobUrlHelper->__invoke(
                $job,
                [
                    'linkOnly'=> true,
                    'absolute' => true,
                ],
                [
                    'lang' => 'de'
                ]
            );
            //HINT: workaround to get base url
            $jobUrl = str_replace('http://', 'https://www.gastrojob24.ch', $jobUrl);

            // DEV Mode
            if (!file_exists($this->configPath . GoogleIndexApi::AUTH_FILE)) {
                $this->logger->info('DEV MODE: GOOGLE INDEX API: Single job with publish date in future. Send ' .
                    GoogleIndexApi::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
                return;
            }
            else {
                $this->logger->info('GOOGLE INDEX API: Single job with publish date in future. Send ' .
                    GoogleIndexApi::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
                $response = $this->sendGoogleRequest($jobUrl);
            }
        }
    }

    private function getCrawlerJobsQuery($fromDate, $toDate)
    {
        $orArray = [];

        foreach($this->options->getCrawler()['organizations'] as $organizationId => $organizationValues) {
            $orArray[] = ['organization' => new ObjectId($organizationId)];
        }

        return [
            '$and' => [
                ['dateCreated.date' => ['$lt' => $toDate]],
                ['dateCreated.date' => ['$gt' => $fromDate]],
                ['$or' => $orArray]
            ]
        ];
    }

    /**
     * Single jobs has publish date set in template values
     * @param $fromDate
     * @param $toDate
     * @return \array[][][]
     */
    private function getSingleJobsQuery($fromDate, $toDate)
    {
        return [
            '$and' => [
                ['datePublishStart.date' => ['$lte' => $toDate]],
                ['datePublishStart.date' => ['$gte' => $fromDate]],
                ['templateValues._freeValues.publishDate' => ['$exists' => true]],
            ]
        ];
    }

    private function sendGoogleRequest($jobUrl)
    {
        $client = new \Google_Client();
        $client->setApplicationName("gastrojob24-apis");
        $client->setAuthConfig($this->configPath . GoogleIndexApi::AUTH_FILE);
        $client->addScope(\Google_Service_Indexing::INDEXING);

        $service = new \Google_Service_Indexing($client);

        $notification = new \Google_Service_Indexing_UrlNotification();
        $notification->setType(GoogleIndexApi::GOOGLE_API_UPDATED_TYPE);
        $notification->setUrl($jobUrl);

        try {
            $response = $service->urlNotifications->publish($notification);
        }
        catch (\Exception $e) {
            $this->logger->err('Google Indexing API : An error occurred. Message: "{message}"', ['exception' => $e, 'message' => $e->getMessage()]);
            $response = false;
        }
        $this->logger->info('Google Indexing API : successful.', ['latestUpdate' => $response->getUrlNotificationMetadata()->getLatestUpdate()]);

        return $response;
    }

    private function jobHasRedirectOrLink($job)
    {
        $hasJobTemplate = $this->jobTemplateHelper->__invoke($job->getOrganization());
        $isIntern = (!$job->getLink() || $hasJobTemplate);
        $isEmbeddable = $this->jobTemplateHelper->__invoke($job->getLink());
        $jobHasExternLink = (!$isIntern && !$isEmbeddable);

        if ($hasJobTemplate) {
            return false;
        }

        if (!$isIntern) {
            return true;
        }

        if ($jobHasExternLink) {
            return true;
        }

        return false;
    }
}