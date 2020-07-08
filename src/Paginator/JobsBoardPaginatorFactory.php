<?php
namespace Gastro24\Paginator;

use Core\Paginator\PaginatorService;
use Interop\Container\ContainerInterface;
use Solr\Bridge\ResultConverter;
use Solr\Paginator\Adapter\SolrAdapter;
use Solr\Paginator\JobsBoardPaginatorFactory as BaseJobsBoardPaginatorFactory;

class JobsBoardPaginatorFactory extends BaseJobsBoardPaginatorFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var PaginatorService $serviceLocator */
        /* @var ResultConverter $resultConverter */
        $filter             = $container->get('FilterManager')->get($this->getFilter());
        $moduleOptions      = $container->get('Solr/Options/Module');
        $connectPath        = $this->getConnectPath($moduleOptions);
        $solrClient         = $container->get('Solr/Manager')->getClient($connectPath);
        $resultConverter    = $container->get('Solr/ResultConverter');
        $adapter            = new SolrAdapter($solrClient, $filter, $resultConverter, new JobsBoardFacets(), $options);
        $service            = new \Solr\Paginator\Paginator($adapter);

        return $service;
    }

}