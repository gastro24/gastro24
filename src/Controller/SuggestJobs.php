<?php

namespace Gastro24\Controller;

use SolrRestApiClient\Api\Client\Domain\Synonym\SynonymCollection;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * SuggestJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SuggestJobs extends AbstractActionController
{
    /**
     * @var \Solr\Options\ModuleOptions
     */
    protected $moduleOptions;

    /**
     * SuggestJobs constructor.
     * @param \Solr\Options\ModuleOptions $moduleOptions
     */
    public function __construct($moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        /* @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
        $searchTerm = $request->getQuery('q');
        $host = $this->moduleOptions->getHostname();
        $port = $this->moduleOptions->getPort();
        $jobsPath = $this->moduleOptions->getJobsPath() . '/';

        $factory = new \SolrRestApiClient\Common\Factory();
        $repository = $factory->getSynonymRepository($host,$port,$jobsPath);

        /** @var SynonymCollection $data */
        $data = $repository->getAll('german');
        $collection = $data->toArray();

        foreach ($collection as $item) {
            if (strpos($item->getMainWord(), $searchTerm) !== false) {
                foreach ($item->getWordsWithSameMeaning() as $word => $wordString) {
                    $results[$word] = [
                        'value' => $wordString,
                        'id' => $wordString,
                        'label' => $wordString
                    ];
                }

            }
        }

        $results = array_slice($results, 0, 10);
        $response->setContent(json_encode($results));

        return $response;
    }
}