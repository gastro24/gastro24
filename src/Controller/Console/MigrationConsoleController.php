<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Jobs\Entity\StatusInterface;
use Laminas\Log\LoggerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Orders\Entity\Order;
use Solr\Filter\EntityToDocument\JobEntityToSolrDocument as EntityToDocumentFilter;

/**
 * Console Controller for migrations
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class MigrationConsoleController extends AbstractActionController
{
    /**
     * @var RepositoryService
     */
    private $repositories;

    /**
     * @var SolrClient
     */
    private $solrClient;

    /**
     * @var EntityToDocumentFilter
     */
    protected $entityToDocumentFilter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        $solrClient,
        RepositoryService $repositories,
        EntityToDocumentFilter $entityToDocumentFilter,
        $logger
    ) {
        $this->solrClient = $solrClient;
        $this->repositories = $repositories;
        $this->entityToDocumentFilter = $entityToDocumentFilter;
        $this->logger = $logger;
    }

    public static function factory(ContainerInterface $container)
    {
        $manager = $container->get('Solr/Manager');
        $options = $container->get('Solr/Options/Module');
        $solrClient = $manager->getClient($manager->getOptions()->getJobsPath());

        return new self(
            $solrClient,
            $container->get('repositories'),
            new EntityToDocumentFilter($options),
            $container->get('Core/Log')
        );
    }

    public function migrateAction()
    {
        echo 'Start migration' . PHP_EOL;

        /** @var \Orders\Repository\Orders $ordersRepo */
        $ordersRepo = $this->repositories->get('Orders');
        $todayDateString = date('Y-m-d');

        // fetch single jobs
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

            //skip jobs not active
            if ($job->getStatus()->getName() !== StatusInterface::ACTIVE) {
                $this->logger->info("Skip Job, status not active. ID: " . $job->getId());
               // echo "Skip Job, status not active. ID: " . $job->getId() . PHP_EOL;

                continue;
            }

            $this->logger->info("Active single job. ID: " . $job->getId());
            echo "Active single job. ID: " . $job->getId() . PHP_EOL;
        }
    }
}