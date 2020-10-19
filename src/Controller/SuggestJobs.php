<?php

namespace Gastro24\Controller;

use SolrRestApiClient\Api\Client\Domain\Synonym\SynonymCollection;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * SuggestJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SuggestJobs extends AbstractActionController
{
    const SUGGEST_LIMIT = 5;

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
        /* @var \Laminas\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        /* @var \Laminas\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
        $searchTerm = $request->getQuery('q');
        $host = $this->moduleOptions->getHostname();
        $port = $this->moduleOptions->getPort();
        $jobsPath = $this->moduleOptions->getJobsPath() . '/';
        $authArray = [
            'auth' => [$this->moduleOptions->getUsername(), $this->moduleOptions->getPassword()]
        ];

        $factory = new \SolrRestApiClient\Common\Factory();
        $repository = $factory->getSynonymRepository($host,$port,$jobsPath, [$this->moduleOptions->getUsername(), $this->moduleOptions->getPassword()]);

        /** @var SynonymCollection $data */
        $data = $repository->getAll('german', $authArray);
        $collection = $data->toArray();
        $results = [];

        foreach ($collection as $item) {
            if (stripos($item->getMainWord(), $searchTerm) !== false) {
                foreach ($item->getWordsWithSameMeaning() as $word => $wordString) {
                    $results[] = $wordString;
                }
            }
        }
        $results = array_unique($results);

        //@see https://stackoverflow.com/a/37974879/8465974
        usort($results,function($a,$b) use ($searchTerm) {
            // prioritize exact matches first
            if ($a == $searchTerm) return -1;
            if ($b == $searchTerm) return 1;

            // prioritize terms containing the keyword next
            $x = stripos($a, $searchTerm);
            $y = stripos($b, $searchTerm);
            if ($x !== false && $y === false) return -1;
            if ($y !== false && $x === false) return 1;
            if ($x !== false && $y !== false) {  // both terms contain the keyword, so...

                if ($x != $y) {  // prioritize matches closer to the beginning of the term
                    return $x > $y ? 1 : -1;
                }

                // both terms contain the keyword at the same position, so...
                $al = strlen($a);
                $bl = strlen($b);
                if ($al != $bl) { // prioritize terms with fewer characters other than the keyword
                    return $al > $bl ? 1 : -1;
                }
                // both terms contain the same number of additional characters
                return 0;
                // or sort alphabetically with strcmp($a, $b);
                // or do additional checks...
            }
            // neither terms contain the keyword

            // check the character similarity...
            $ac = levenshtein($searchTerm, $a);
            $bc = levenshtein($searchTerm, $b);
            if ($ac != $bc) {
                return $ac > $bc ? 1 : -1;
            }

            return 0;
            // or sort alphabetically with strcmp($a, $b);
            // or do additional checks, similar_text, etc.
        });

        $results = array_slice($results, 0, self::SUGGEST_LIMIT);
        $finalResults = [];
        foreach ($results as $word) {
            $finalResults[$word] = [
                'value' => $word,
                'id' => $word,
                'label' => preg_replace("/($searchTerm)/i", sprintf('<strong>$1</strong>'), $word )
            ];
        }

        $response->setContent(json_encode($finalResults));

        return $response;
    }


}