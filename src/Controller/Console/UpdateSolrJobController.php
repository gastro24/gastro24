<?php

namespace Gastro24\Controller\Console;

use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Jobs\Entity\Job;
use Laminas\Mvc\Controller\AbstractActionController;
use Solr\Filter\EntityToDocument\JobEntityToSolrDocument as EntityToDocumentFilter;

/**
 * Console controller for updating single job in solr.
 *
 * @package Gastro24\Controller\Console
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class UpdateSolrJobController extends AbstractActionController
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

    public function updateJobInSolrAction()
    {
        $jobId = $this->params('jobId');
        if (!$jobId) {
            return;
        }

        /* @var \Jobs\Repository\Job $jobsRepo */
        $jobsRepo = $this->repositories->get('Jobs/Job');
        /** @var Job $jobEntity */
        $jobEntity = $jobsRepo->find($jobId);

        $document = $this->entityToDocumentFilter->filter($jobEntity);
        $this->solrClient->addDocument($document);

        // commit to index & optimize it
        $this->solrClient->commit(true, false);
        $this->solrClient->optimize(1, true, false);
    }

}