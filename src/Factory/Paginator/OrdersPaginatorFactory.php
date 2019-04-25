<?php

namespace Gastro24\Factory\Paginator;

use Core\Paginator\PaginatorFactoryAbstract;
use Orders\Repository\Filter\PaginationQuery;

/**
 * OrdersPaginatorFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrdersPaginatorFactory extends PaginatorFactoryAbstract
{
    protected function getFilter()
    {
        return PaginationQuery::class;
    }

    protected function getRepository()
    {
        return 'Gastro24/Order';
    }
}