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
 * DeleteJobsController.php
 *
 * Console Controller which deletes expired jobs based on options. (@see \Gastro24\Options\ConsoleDeleteJobs)
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class DeleteJobsController extends AbstractActionController
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
     * @var bool
     */
    private $onlyDebug = false;

    public function __construct(
        RepositoryService $repositories,
        ConsoleDeleteJobs $options,
        $logger
    ) {
        $this->repositories = $repositories;
        $this->options = $options;
        $this->logger = $logger;
    }

    public static function factory(ContainerInterface $container)
    {
        return new self(
            $container->get('repositories'),
            $container->get(\Gastro24\Options\ConsoleDeleteJobs::class),
            $container->get('Core/Log')
        );
    }

    public function deleteExpiredJobsAction()
    {
        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo = $this->repositories->get('Jobs/Job');
        $orgRepo = $this->repositories->get('Organizations/Organization');
        $this->onlyDebug = (bool) $this->params('onlyDebug');
        $orgKeys = [];

        echo "Clear crawler jobs ...\n";
        foreach($this->options->getCrawler()['organizations'] as $organizationId => $organizationValues) {
            $days = $organizationValues['days'];
            $date = new \DateTime('today');
            $date->sub(new \DateInterval('P' . $days . 'D'));

            $org = $orgRepo->findOneById($organizationId);
            if (!$org) {
                $this->logger->info("Delete expired jobs: Organization " . $organizationValues['name'] . " not found");
                continue;
            }

            $query = $this->getQueryForCrawlerJobs($date, $organizationId);
            $jobs = $jobsRepo->findBy($query);

            echo count($jobs) . " jobs found for " . $organizationValues['name'] . ".\n";
            $this->logger->info("Delete expired jobs: " . count($jobs) . " jobs found for " . $organizationValues['name']);

            $this->clearJobs($jobsRepo, $jobs, $date);
            $orgKeys[] = new ObjectId($organizationId);
        }

        echo "Clear single jobs ...\n";
        $days = $this->options->getSingle()['days'];
        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $days . 'D'));
        $query = $this->getQueryForSingleJobs($date, $orgKeys);
        $jobs = $jobsRepo->findBy($query);
        $this->logger->info("Delete expired single jobs: " . count($jobs) . " jobs found.");
        $this->clearJobs($jobsRepo, $jobs, $date);

        echo "Clear paid jobs ...\n";
        $days = $this->options->getPaid()['days'];
        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $days . 'D'));
        $query = $this->getQueryForPaidJobs($date, $orgKeys);
        $jobs = $jobsRepo->findBy($query);
        $this->logger->info("Delete expired paid jobs: " . count($jobs) . " jobs found.");
        $this->clearJobs($jobsRepo, $jobs, $date);
    }

    /**
     * @param \Jobs\Repository\Job $jobsRepo
     * @param $jobs
     * @return string
     */
    private function clearJobs($jobsRepo, $jobs, $date)
    {
        // filter history expired date
        $filteredJobs = $jobs;
        /* @var \Jobs\Entity\Job $job */
        foreach ($jobs as $index => $job) {
            $expiredHistoryEntry = $job->getHistory()->last();

            if ($expiredHistoryEntry->getDate() > $date) {
                unset($filteredJobs[$index]);
            }
        }

        $jobs = $filteredJobs;
        $count = count($jobs);

        if (0 === $count) {
            echo "No jobs found.\n";
            return PHP_EOL;
        }

        echo "$count jobs found, which will be deleted ...\n";
        $this->logger->info("Delete expired jobs: " . $count . " jobs will be deleted");

        $progress = new ProgressBar(
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

        // quit here if only debug ios enabled
        if ($this->onlyDebug) {
            echo "Only debug enabled, no database changes.\n";
            return PHP_EOL;
        }

        // remove from SOLR
        /* @var \Jobs\Entity\Job $job */
        foreach ($jobs as $job) {
            $job->delete();
        }
        $this->repositories->flush();

        // remove from MongoDB
        /* @var \Jobs\Entity\Job $job */
        foreach ($jobs as $job) {
            $progress->update($i++, 'Job ' . $i . ' / ' . $count);

            $jobsRepo->remove($job);

            if (0 == $i % 500) {
                $progress->update($i, 'Remove from database...');
                $this->repositories->flush();
            }
        }

        $progress->update($i, 'Remove from database...');
        $this->repositories->flush();
        $progress->update($i, 'Done');
        $progress->finish();

        return PHP_EOL;
    }

    private function getQueryForSingleJobs($date, $orgKeys)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::EXPIRED],
                ['history.status.name' => StatusInterface::EXPIRED],
                ['user' => ['$exists' => false]],
                ['organization' => ['$nin' => $orgKeys]],
            ]
        ];
    }

    private function getQueryForPaidJobs($date, $orgKeys)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::EXPIRED],
                ['history.status.name' => StatusInterface::EXPIRED],
                ['user' => ['$exists' => true]],
                ['organization' => ['$nin' => $orgKeys]],
            ]
        ];
    }

    private function getQueryForCrawlerJobs($date, $orgId)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::EXPIRED],
                ['history.status.name' => StatusInterface::EXPIRED],
                ['user' => ['$exists' => true]],
                ['organization' => new ObjectId($orgId)],
            ]
        ];
    }
}