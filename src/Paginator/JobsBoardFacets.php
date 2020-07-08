<?php
namespace Gastro24\Paginator;

use Solr\Facets;

class JobsBoardFacets extends Facets
{
    const TYPE_QUERIES = 'facet_queries';
    const KEY_24_HOURS = 'datePublishStart:[NOW-1DAY TO NOW]';
    const KEY_3_DAYS = 'datePublishStart:[NOW-3DAYS TO NOW]';
    const KEY_7_DAYS = 'datePublishStart:[NOW-7DAYS TO NOW]';
    const KEY_30_DAYS = 'datePublishStart:[NOW-30DAYS TO NOW]';

    public static $formattedPublishDateValue = [
        '1440' => 'Neuer als 24 h',
        '4320' => 'Neuer als 3 Tage',
        '10080' => 'Neuer als 7 Tage',
        '43200' => 'Neuer als 30 Tage',
    ];

    public function getFacetQueries()
    {
        return $this->facetResult[self::TYPE_QUERIES];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (!isset($this->cache)) {
            $this->cache = [];

            foreach ($this->definitions as $name => $definition) {
                // check if the facet definition exists in the facet result
                if (!isset($this->facetResult[$definition['type']])
                    || !isset($this->facetResult[$definition['type']][$name])
                ) {
                    continue;
                }

                // cast to array to workaround the 'Notice: Illegal member variable name' for PHP <= 5.6.20
                $result = (array)$this->facetResult[$definition['type']][$name];
                // remove empty value
                unset($result['']);

                $this->cache[$name] = $result;
            }

            // add facet queries
            $facetQueriesData = (array)$this->facetResult[self::TYPE_QUERIES];
            $this->cache = array_merge([
                'publishDateFilter' => [
                    '1440' => $facetQueriesData[self::KEY_24_HOURS], //24 hours
                    '4320' => $facetQueriesData[self::KEY_3_DAYS], //3 days
                    '10080' => $facetQueriesData[self::KEY_7_DAYS], //7 days
                    '43200' => $facetQueriesData[self::KEY_30_DAYS], //30 hours
                ]
            ], $this->cache);
        }

        return $this->cache;
    }

    /**
     * @return array
     */
    public function getActiveValues()
    {
        $return = [];

        foreach ($this->toArray() as $name => $values) {
            if (isset($this->params[$name])
                && is_array($this->params[$name])
                && $this->params[$name]
            ) {
                $activeValues = [];

                foreach ($values as $value => $count) {
                    if (isset($this->params[$name][$value])) {
                        $activeValues[] = $value;
                    }
                }

                if ($activeValues) {
                    $return[$name] = $activeValues;
                }
            }
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function getTitle($name)
    {
        if ($name === 'publishDateFilter') {
            return 'Erscheinungsdatum';
        }

        return parent::getTitle($name);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     */
    protected function assertValidName($name)
    {
        if (! isset($this->definitions[$name]) && $name !== 'publishDateFilter') {
            throw new \InvalidArgumentException();
        }
    }

}