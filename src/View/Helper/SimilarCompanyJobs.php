<?php

namespace Gastro24\View\Helper;

use Gastro24\Validator\IframeEmbeddableUri;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * SimilarJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SimilarCompanyJobs extends AbstractHelper
{
    const ITEM_PER_PAGE_COUNT = 15;

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
        $searchQueryString = '(' . $keywordString . ') AND NOT id:"' . $currentJob->getId() . '" AND isActive:true';

        // exclude jobs from same company
        if ($currentJob->getOrganization()) {
            $organisationName = $currentJob->getOrganization()->getOrganizationName();
            $searchQueryString .= ' AND organizationName:"' . $organisationName . '"';
        }
        else if ($currentJob->getCompany()) {
            $searchQueryString .= ' AND organizationName:"' . $currentJob->getCompany() . '"';
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
            $searchQueryString = '(' . $keywordString . ') AND industry_MultiString:(' . $industryString . ') AND NOT id:"' . $currentJob->getTitle() . '"  AND isActive:true';
        }

        $jobBoardQueryParams = ['q' => $searchQueryString, 'page' => 1, 'count' => self::ITEM_PER_PAGE_COUNT];
        $jobBoardParams = [
            'Jobs_Board',
            'q' => $searchQueryString,
            'count' => self::ITEM_PER_PAGE_COUNT,
            $jobBoardQueryParams
        ];

        $paginator  = $this->paginators->get('Gastro24/Jobs/Similar', $jobBoardParams);
        $paginator->setItemCountPerPage(self::ITEM_PER_PAGE_COUNT);

        $jobs = [];
        $counterAll = 0;
        $counter = 0;
        $page = 1;
        $maxItems = $paginator->getTotalItemCount();

        if ($maxItems < 7) {
            $searchParts = explode('AND', $searchQueryString, 2);
            $searchQueryString = $searchParts[1];
            // reset keywords filter
            $jobBoardParams['q'] = $searchQueryString;
            $jobBoardQueryParams['q'] = $searchQueryString;
            $paginator  = $this->paginators->get('Gastro24/Jobs/Similar', $jobBoardParams);
            $paginator->setItemCountPerPage(self::ITEM_PER_PAGE_COUNT);
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