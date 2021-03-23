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

    public function updateSingleProAction()
    {
        /** @var \Orders\Repository\Orders $ordersRepo */
        $ordersRepo = $this->repositories->get('Orders');
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

            // update publish start date after 15 days
            if ($jobDateString == $todayDateString) {
                $this->logger->info("Update publish date of job. ID: " . $job->getId());
                echo "Update publish date of job. ID: " . $job->getId() . PHP_EOL;

                // HINT: set publishEnd for JsonLD - validThrough
                $oldDateStart = $job->getDatePublishStart();
                $dateEnd = new \DateTime($oldDateStart);
                $dateEnd->add(new \DateInterval("P30D"));
                $job->setDatePublishEnd($dateEnd);

                // update publish start date
                $job->setDatePublishStart();
                $this->repositories->store($job);

                $document = $this->entityToDocumentFilter->filter($job);
                $this->solrClient->addDocument($document);

                // commit to index & optimize it
                $this->solrClient->commit(true, false);
                $this->solrClient->optimize(1, true, false);
            }
        }
        $this->repositories->flush();
    }
}