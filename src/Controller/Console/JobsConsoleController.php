<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Gastro24\Options\ConsoleDeleteJobs;
use Interop\Container\ContainerInterface;
use Jobs\Entity\StatusInterface;
use Jobs\Listener\Events\JobEvent;
use MongoDB\BSON\ObjectId;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ProgressBar\Adapter\Console as ConsoleAdapter;
use Zend\ProgressBar\ProgressBar;

/**
 * JobsConsoleController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobsConsoleController extends AbstractActionController
{
    /**
     * @var RepositoryService
     */
    private $repositories;
    private $mailer;
    private $jobEvents;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConsoleDeleteJobs
     */
    private $options;

    public function __construct(
        RepositoryService $repositories,
        \Core\Mail\MailService $mailer,
        $jobEvents,
        $logger,
        ConsoleDeleteJobs $options
    ) {
        $this->repositories = $repositories;
        $this->mailer = $mailer;
        $this->jobEvents = $jobEvents;
        $this->logger = $logger;
        $this->options = $options;
    }

    public static function factory(ContainerInterface $container)
    {
        return new self(
            $container->get('repositories'),
            $container->get('Core/MailService'),
            $container->get('Jobs/Events'),
            $container->get('Core/Log'),
            $container->get(\Gastro24\Options\ConsoleDeleteJobs::class)
        );
    }

    public function expireJobsAction()
    {
        $repositories = $this->repositories;
        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo     = $repositories->get('Jobs/Job');
        $days = (int) $this->params('days');
        $limit = (string) $this->params('limit');
        $info = $this->params('info');

        $crawlerDays = 90;
        $orgKeys = [];

        if (!$days) {
            return 'Invalid value for --days. Must be integer.';
        }

        $offset = 0;
        if ($limit && false !== strpos($limit, ',')) {
            list($limit, $offset) = explode(',', $limit);
        }

        // mark crawler jobs as expired
        echo "Expire crawler jobs ...\n";
        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $crawlerDays . 'D'));

        foreach($this->options->getCrawler()['organizations'] as $organizationId => $organizationValues) {
            $paidQuery = $this->getPaidJobsQuery($date, $organizationId);
            $jobs = $jobsRepo->findBy($paidQuery);
            $this->logger->info("Cron: Expire jobs: Mark " . count($jobs) . " paid jobs as expired (" . $organizationValues['name'] . ").");
            echo "Expire " . count($jobs) ." jobs of " . $organizationValues['name'] . ".\n";
            $this->markAsExpired($repositories, $jobs);

            $orgKeys[] = new ObjectId($organizationId);
        }

        // mark single jobs as expired
        echo "Expire single jobs ...\n";
        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $days . 'D'));
        $singleQuery = $this->getSingleJobsQuery($date, $orgKeys);
        $jobs = $jobsRepo->findBy($singleQuery, null, (int) $limit, (int) $offset);
        $count = count($jobs);
        $this->logger->info("Cron: Expire jobs: Mark " . $count . " single jobs as expired.");

        echo "Expire " . $count . " single jobs.\n";
        if (0 === $count) {
            return 'No jobs found.';
        }

        $this->printInfo($info, $offset, $jobs);
        $this->markAsExpired($repositories, $jobs);

        return PHP_EOL;
    }

    private function printInfo($info, $offset, $jobs)
    {
        if ($info) {
            echo count($jobs) , ' Jobs';
            if ($offset) {
                echo ' starting from ' . $offset;
            }
            echo PHP_EOL . PHP_EOL;
            $this->listExpiredJobs($jobs);
            return;
        }
    }

    private function markAsExpired($repositories, $jobs)
    {
        $count = count($jobs);
        $progress     = new ProgressBar(
            new ConsoleAdapter(
                array(
                    'elements' => array(
                        ConsoleAdapter::ELEMENT_TEXT,
                        ConsoleAdapter::ELEMENT_BAR,
                        ConsoleAdapter::ELEMENT_PERCENT,
                        ConsoleAdapter::ELEMENT_ETA
                    ),
                    'textWidth' => 20,
                    'barLeftChar' => '-',
                    'barRightChar' => ' ',
                    'barIndicatorChar' => '>',
                )
            ),
            0,
            count($jobs)
        );

        $i = 0;

        /* @var \Jobs\Entity\Job $job */
        foreach ($jobs as $job) {
            $progress->update($i++, 'Job ' . $i . ' / ' . $count);

            $job->changeStatus('expired', 'Job was set to expired by cron');

            if (0 == $i % 500) {
                $progress->update($i, 'Write to database...');
                $repositories->flush();
            }

            $this->jobEvents->trigger(JobEvent::EVENT_STATUS_CHANGED, $this, ['job' => $job, 'status' => $job->getStatus()]);
        }
        $progress->update($i, 'Write to database...');
        $repositories->flush();
        $progress->update($i, 'Done');
        $progress->finish();
    }

    private function getSingleJobsQuery($date, $orgKeys)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::ACTIVE],
                ['$or' => [
                    ['datePublishStart.date' => ['$lt' => $date]],
                    ['datePublishEnd.date' => ['$lt' => new \DateTime('today midnight')]],
                ]],
                ['$or' => [
                    ['isDeleted' => ['$exists' => false]],
                    ['isDeleted' => false],
                ]],
                ['user' => ['$exists' => false]], // fetch only Einzelinserate
                ['organization' => ['$nin' => $orgKeys]],
            ]
        ];
    }

    private function getPaidJobsQuery($date, $orgId)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::ACTIVE],
                ['$or' => [
                    ['datePublishStart.date' => ['$lt' => $date]],
                    ['datePublishEnd.date' => ['$lt' => new \DateTime('today midnight')]],
                ]],
                ['$or' => [
                    ['isDeleted' => ['$exists' => false]],
                    ['isDeleted' => false],
                ]],
                ['user' => ['$exists' => false]],
                ['organization' => new ObjectId($orgId)],
            ]
        ];
    }

    private function listExpiredJobs($jobs)
    {
        /* @var \Jobs\Entity\JobInterface $job */
        foreach ($jobs as $job) {
            $id = $job->getId();
            $org = $job->getOrganization();
            if ($org) {
                $org = $org->getName();
            } else {
                $org = $job->getCompany();
            }
            printf(
                '%s   %s   %s   %-30s   %-20s' . PHP_EOL,
                $id,
                $job->getDatePublishStart()->format('Y-m-d'),
                $job->getDatePublishEnd()->format('Y-m-d'),
                substr($job->getTitle(), 0, 30),
                substr($org, 0, 20)
            );
        }
        return count($jobs) . ' Jobs.';
    }
}