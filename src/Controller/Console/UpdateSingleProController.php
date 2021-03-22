<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Jobs\Entity\StatusInterface;
use Laminas\Log\LoggerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Orders\Entity\Order;

/**
 * Console Controller which updated publishedDate of Single Pro Jobs
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class UpdateSingleProController extends AbstractActionController
{
    /**
     * @var RepositoryService
     */
    private $repositories;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RepositoryService $repositories,
        $logger
    ) {
        $this->repositories = $repositories;
        $this->logger = $logger;
    }

    public static function factory(ContainerInterface $container)
    {
        return new self(
            $container->get('repositories'),
            $container->get('Core/Log')
        );
    }

    public function updateSingleProAction()
    {
        /** @var \Orders\Repository\Orders $ordersRepo */
        $ordersRepo = $this->repositories->get('Orders');
        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo = $this->repositories->get('Jobs/Job');
        $todayDateString = date('Y-m-d');

        $singleOrders = $ordersRepo->findBy([
            '$and' => [
                ['type' => 'job'],
                ['products' => [
                    '$elemMatch' => [
                        'name' => 'Einzelinserat'
                    ]
                ]]
            ]
        ]);

        /** @var Order $order */
        foreach ($singleOrders as $order) {
            /* @var \Jobs\Entity\Job $job */
            $job = $order->getEntity()->getEntity();

            if (!$job) {
                continue;
            }

            //TODO: skip jobs not active
            if ($job->getStatus()->getName() !== StatusInterface::ACTIVE) {
                $this->logger->info("Skip Job, status not active. ID: " . $job->getId());
                //echo "Skip Job, status not active. ID: " . $job->getId() . PHP_EOL;

                continue;
            }

            $jobDate = $job->getDatePublishStart() ?? $job->getDateCreated();
            $jobDateString = $jobDate->modify('+15 days')->format('Y-m-d');
//            echo "Job Date: " .$jobDateString . PHP_EOL;
//            echo "Today: " . $todayDateString . PHP_EOL;

            // update publish start date after 15 days
            if ($jobDateString == $todayDateString) {
                $this->logger->info("Update publish date of job. ID: " . $job->getId());
                echo "Update publish date of job. ID: " . $job->getId() . PHP_EOL;

                $job->setDatePublishStart();
                $jobsRepo->store($job);
            }
        }
        $jobsRepo->flush();
    }
}