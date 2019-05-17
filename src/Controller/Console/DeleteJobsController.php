<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Gastro24\Options\ConsoleDeleteJobs;
use Interop\Container\ContainerInterface;
use Jobs\Entity\StatusInterface;
use Jobs\Listener\Events\JobEvent;
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

    public function __construct(
        RepositoryService $repositories,
        ConsoleDeleteJobs $options
    ) {
        $this->repositories = $repositories;
        $this->options = $options;
    }

    public static function factory(ContainerInterface $container)
    {
        return new self(
            $container->get('repositories'),
            $container->get(\Gastro24\Options\ConsoleDeleteJobs::class)
        );
    }

    public function deleteExpiredJobsAction()
    {
        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo = $this->repositories->get('Jobs/Job');
        $orgRepo = $this->repositories->get('Organizations/Organization');

        echo "Clear paid jobs ...\n";
        $days = $this->options->getPaid()['days'];
        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $days . 'D'));

        $orgKeys = array_keys($this->options->getCrawler()['organizations']);
        $query = $this->getQueryForPaidJobs($date, $orgKeys);
        $jobs = $jobsRepo->findBy($query);
        $this->clearJobs($jobsRepo, $jobs);


        echo "Clear crawler jobs ...\n";
        foreach($this->options->getCrawler()['organizations'] as $organization => $values) {
            $days = $values['days'];
            $date = new \DateTime('today');
            $date->sub(new \DateInterval('P' . $days . 'D'));

            $org = $orgRepo->findOneBy(['_organizationName' => $organization]);
            if (!$org) {
                continue;
            }

            $query = $this->getQueryForCrawlerJobs($date, $org->getId());
            $jobs = $jobsRepo->findBy($query);

            $this->clearJobs($jobsRepo, $jobs);
        }
    }

    /**
     * @param \Jobs\Repository\Job $jobsRepo
     * @param $jobs
     * @return string
     */
    private function clearJobs($jobsRepo, $jobs)
    {
        $count = count($jobs);

        if (0 === $count) {
            echo "No jobs found.\n";
            return PHP_EOL;
        }

        echo "$count jobs found, which will be deleted ...\n";

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

    private function getQueryForPaidJobs($date, $orgKeys)
    {
        return [
            '$and' => [
                ['status.name' => StatusInterface::EXPIRED],
                ['$and' => [
                    ['history.status.name' => StatusInterface::EXPIRED],
                    ['history.date.date' => ['$lt' => $date]],
                ]],
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
                ['$and' => [
                    ['history.status.name' => StatusInterface::EXPIRED],
                    ['history.date.date' => ['$lt' => $date]],
                ]],
                ['user' => ['$exists' => true]],
                ['organization' => ['$eq' => $orgId]],
            ]
        ];
    }
}