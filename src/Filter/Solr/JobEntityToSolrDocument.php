<?php

namespace Gastro24\Filter\Solr;

use Gastro24\Options\Landingpages;
use Solr\Filter\EntityToDocument\JobEntityToSolrDocument as BaseJobEntityToSolrDocument;
use Jobs\Entity\Job as JobEntity;
use Solr\Options\ModuleOptions;

class JobEntityToSolrDocument extends BaseJobEntityToSolrDocument
{
    /**
     * @var $landingPageOptions Landingpages
     */
    protected $landingPageOptions;

    public function __construct($options, $landingPageOptions)
    {
        parent::__construct($options);
        $this->landingPageOptions = $landingPageOptions;
    }

    /**
     * @inheridoc
     */
    public function filter($job)
    {
        $document = parent::filter($job);
        $templateValues = $job->getTemplateValues();
        if ($category = $templateValues->get('category')) {
            $catQueryParams = $this->landingPageOptions->getQueryParameters($category);
            $document->addField('category_s', $category);
            $document->addField('categoryName_s', $catQueryParams['q']);

            $categories = $templateValues->get('categories');
            foreach ($categories as $landingPageCategory) {
                $queryParams = $this->landingPageOptions->getQueryParameters($landingPageCategory);
                $document->addField('categories_MultiString', $queryParams['q']);
            }
        }

        return $document;
    }
}