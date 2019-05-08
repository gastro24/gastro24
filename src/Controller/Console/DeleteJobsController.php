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
        //TODO: get days from options
        $days = 30;

        $date = new \DateTime('today');
        $date->sub(new \DateInterval('P' . $days . 'D'));

        $query = [
            '$and' => [
                ['status.name' => StatusInterface::EXPIRED],
                ['$or' => [
                    ['datePublishStart.date' => ['$lt' => $date]],
                    ['datePublishEnd.date' => ['$lt' => new \DateTime('today midnight')]],
                ]],
                ['user' => ['$exists' => false]], // fetch only Einzelinserate
            ]
        ];

        $jobs = $jobsRepo->findBy($query);
        $count = count($jobs);

        if (0 === $count) {
            return 'No jobs found.';
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
}