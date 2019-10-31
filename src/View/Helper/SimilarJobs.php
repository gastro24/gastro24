<?php

namespace Gastro24\View\Helper;

use Gastro24\Validator\IframeEmbeddableUri;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * SimilarJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SimilarJobs extends AbstractHelper
{
    /**
     * @var $paginators
     */
    private $paginators;

    /**
     * @var IframeEmbeddableUri
     */
    private $validator;

    public function __construct($paginators, $validator)
    {
        $this->paginators = $paginators;
        $this->validator = $validator;
    }

    /**
     * @param \Jobs\Entity\Job $currentJob
     * @return array
     */
    public function __invoke($currentJob, $maxResults = 6)
    {
        /** @var \Solr\Paginator\Paginator $paginator */
        $keywordParts = explode(' ', $currentJob->getTitle());

        // remove special characters from search query
        $removeSpecialChars = array('/', '-', ',', ':', '');
        $keywords = array_diff($keywordParts, $removeSpecialChars);

        $keywordString = implode(' OR ', $keywords);
        $industries = [];
        $searchQueryString = '(' . $keywordString . ') AND NOT "' . $currentJob->getTitle() . '" AND isActive:true';

        // exclude jobs from same company
        if ($currentJob->getOrganization()) {
            $organisationName = $currentJob->getOrganization()->getOrganizationName();
            $searchQueryString .= ' AND NOT organizationName:"' . $organisationName . '"';
        }
        else if ($currentJob->getCompany()) {
            $searchQueryString .= ' AND NOT organizationName:"' . $currentJob->getCompany() . '"';
        }


        foreach ($currentJob->getClassifications()->getIndustries()->getItems() as $industry) {
            // change _ to &
            $parts = explode('_', $industry->getName());
            if (count($parts)) {
                $industryNames = [];
                foreach ($parts as $industryPart) {
                    $industryNames[] = ucfirst($industryPart);
                }
                $industryName = implode(' & ', $industryNames);
                $industries[] = '"' . $industryName . '"';
            }
            else {
                $industries[] = ucfirst($industry->getName());
            }
        }
        if (count($industries)) {
            $industryString = implode(' OR ', $industries);
            $searchQueryString = '(' . $keywordString . ') AND industry_MultiString:(' . $industryString . ') AND NOT "' . $currentJob->getTitle() . '"  AND isActive:true';
        }

        $jobBoardQueryParams = ['q' => $searchQueryString, 'page' => 1, 'd' => 20, 'count' => 50];
        $jobBoardParams = [
            'Jobs_Board',
            'q' => $searchQueryString,
            'd' => 20,
            'count' => 50,
            $jobBoardQueryParams
        ];

        // add distance filter
        if ($currentJob->getLocations()->get(0)) {
            array_pop($jobBoardParams);
            $jobBoardParams['l'] = $currentJob->getLocations()->get(0);
            $jobBoardQueryParams['l'] = $currentJob->getLocations()->get(0);
            $jobBoardParams[] = $jobBoardQueryParams;
        }

        $paginator  = $this->paginators->get('Gastro24/Jobs/Similar', $jobBoardParams);
        $paginator->setItemCountPerPage(50);

        $jobs = [];
        $counterAll = 0;
        $counter = 0;
        $page = 1;
        $maxItems = $paginator->getTotalItemCount();

        if ($maxItems < 1) {
            // reset distance filter
            array_pop($jobBoardParams);
            unset($jobBoardParams['l']);
            unset($jobBoardQueryParams['l']);
            $jobBoardParams[] = $jobBoardQueryParams;

            $paginator  = $this->paginators->get('Gastro24/Jobs/Similar', $jobBoardParams);
            $paginator->setItemCountPerPage(50);
            $maxItems = $paginator->getTotalItemCount();
        }

        while ($counterAll < $maxItems) {
            foreach ($paginator->getItemsByPage($page) as $job) {
                $counterAll++;
                // check for external jobs
                if (!$this->validator->isValid($job->getLink())) {
                    continue;
                }

                $jobs[] = $job;
                $counter++;
                if ($counter == $maxResults) {
                    return $jobs;
                }
            }
            $page++;
            $paginator->setCurrentPageNumber($page);
        }

        return $jobs;
    }
}