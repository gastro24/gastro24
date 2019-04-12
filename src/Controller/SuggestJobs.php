<?php

namespace Gastro24\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * SuggestJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SuggestJobs extends AbstractActionController
{
    /**
     * @var \SolrClient
     */
    protected $client;

    /**
     * SuggestJobs constructor.
     * @param \SolrClient  $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        /* @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
        $searchTerm = $request->getQuery('q');

        //http://localhost:8983/solr/YawikJobs/suggest?suggest=true&suggest.build=true&wt=json&suggest.q=

        $this->client->setServlet(\SolrClient::SEARCH_SERVLET_TYPE, 'suggest');
        $solrQuery  = new \SolrQuery();
        $solrQuery->set('suggest','true');
        $solrQuery->set('suggest.build','true');
        $solrQuery->set('wt','json');
        $solrQuery->setQuery($searchTerm);
        $query_response = $this->client->query($solrQuery);
        $data = $query_response->getResponse();

        if (is_null($data->suggest->infixSuggester)) {
            $response->setContent(json_encode([]));

            return $response;
        }

        $results = [];
        foreach ($data->suggest->infixSuggester as $term => $suggest) {
            foreach ($suggest->suggestions as $suggestionObject) {
                $results[] = [
                    'value' => $suggestionObject->term,
                    'id' => $suggestionObject->term,
                    'label' => $suggestionObject->term
                ];
            }
        }

        $response->setContent(json_encode($results));

        return $response;
    }

}