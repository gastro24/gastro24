<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class Landingpages extends AbstractOptions
{

    private $idMap = [];

    private $queryMap = [];

    private $childsMap = [];

    private $tabs = [];
    
    private $companies = [];

    private $options = [];

    private $parentQueries = [];

    private $categoryValues = [];

    public function setFromArray($options)
    {
        $this->options = $options;
        $categoryValues = [];
        $idMap = [];
        $queryMap = [];
        $childsMap = [];
        $tabs = [];
        $companies = [];

        foreach ($options as $term => $spec) {
            if (isset($spec['id'])) {
                $idMap[$term] = $spec['id'];
            }

            if (isset($spec['company'])) {
                if (!isset($spec['query'])) {
                    $spec['query'] = [ 'organizationTag' => [$spec['company'] => 1]];
                } else if (!isset($spec['query']['organizationTag'])) {
                    $spec['query']['organizationTag'] = [$spec['company'] => 1];
                }
                
                $companies[$term] = [isset($spec['text'])?$spec['text'] : $term, isset($spec['logo']) ? $spec['logo'] : false];
            }

            if (isset($spec['query']) && !isset($spec['query']['q'])) {
                $spec['query']['q'] = '';
            }

            if ($spec['query']['q']) {
                $queryValue = $spec['query']['q'];
                $categoryValues[$term] = $queryValue;
            }

            $queryMap[ $term ] = isset($spec[ 'query' ]) ? $spec[ 'query' ] : ['q' => $term];

            if (isset($spec['tab']) && isset($spec['panel'])) {
                $tabs[ $spec[ 'tab' ] ][ $spec[ 'panel' ] ][] = [$term, $spec[ 'text' ]];
            }

            if (isset($spec['parent'])) {
                $identifier = isset($spec['query']['q']) ? $spec['query']['q'] : $term;
                $this->parentQueries[$identifier] = $spec['parent'];
                $childsMap[$spec['parent']][] = [
                    'child' => $term,
                    'spec'  => $spec
                ];
            }

        }

        return parent::setFromArray([
            'idMap' => $idMap,
            'queryMap' => $queryMap,
            'childsMap' => $childsMap,
            'tabs' => $tabs,
            'companies' => $companies,
            'parentQueries' => $this->parentQueries,
            'categoryValues' => $categoryValues
        ]);
    }

    /**
     * @return array
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param array $companies
     *
     * @return self
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     * @return array
     */
    public function getIdMap($term = null, $default = null)
    {
        if (null === $term) {
            return $this->idMap;
        }

        return isset($this->idMap[$term]) ? $this->idMap[$term] : $default;
    }

    /**
     * @param array $idMap
     *
     * @return self
     */
    public function setIdMap($idMap)
    {
        $this->idMap = $idMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryMap()
    {
        return $this->queryMap;
    }

    public function getQueryParameters($term)
    {
        return isset($this->queryMap[$term]) ? $this->queryMap[$term] : [];
    }

    /**
     * @param array $queryMap
     *
     * @return self
     */
    public function setQueryMap($queryMap)
    {
        $this->queryMap = $queryMap;

        return $this;
    }

    /**
     * @return array
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @param array $tabs
     *
     * @return self
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;

        return $this;
    }

    /**
     * @return array
     */
    public function getParentQueries()
    {
        return $this->parentQueries;
    }

    /**
     * @param array $parentQueries
     *
     * @return self
     */
    public function setParentQueries($parentQueries)
    {
        $this->parentQueries = $parentQueries;

        return $this;
    }

    public function getParentByTerm($term)
    {
        if (isset($this->parentQueries[$term])) {
            return [
                'searchTerm' => $this->parentQueries[$term],
                'parent' => $this->options[$this->parentQueries[$term]]
            ];
        }

        return null;
    }

    public function getChildCategories($parentCategory)
    {
        return $this->childsMap[$parentCategory] ?? [];
    }

    /**
     * @param array $childsMap
     *
     * @return self
     */
    public function setChildsMap($childsMap)
    {
        $this->childsMap = $childsMap;

        return $this;
    }

    public function getChildsMap()
    {
        return $this->childsMap;
    }

    /**
     * @param array $categoryValues
     *
     * @return self
     */
    public function setCategoryValues($categoryValues)
    {
        $this->categoryValues = $categoryValues;

        return $this;
    }

    public function getCategoryValues()
    {
        return $this->categoryValues;
    }

    public function getParentCategories()
    {
        $parents = [];
        foreach ($this->childsMap as $parentKey => $childData) {
            if (empty($this->getQueryParameters($parentKey)['q'])) {
                continue;
            }
            $parents[$parentKey] = $this->getQueryParameters($parentKey)['q'];
        }

        return $parents;
    }
}
