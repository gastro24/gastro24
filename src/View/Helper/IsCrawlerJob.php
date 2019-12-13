<?php

namespace Gastro24\View\Helper;

use Organizations\Entity\Organization;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * IsCrawlerJob.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class IsCrawlerJob extends AbstractHelper
{
    /**
     * @param Organization $org
     * @return bool
     */
    public function __invoke($org)
    {
        if (!$org) {
            return false;
        }

        // TODO: read crawler ids from options
        $crawlerOrganizations = [
            '5a0809397bb2b582267c7a97', // Adecco
            '5bcf612fb6428b0b1008db60', // McDonalds
            '5bc6e561b6428b641322dbf9', // Gilde
            '5aa7e1d77bb2b57b5d3c03e4', // zfv
            '58a4a59d4e197f17047b23c6', // Sprüngli
            '5bcdfda2b6428ba17c5c9048', // Bürkenstock
            '59d7d04e7bb2b594121c1235', // Coop
            '5aaf724f7bb2b5233a03ed25', // Kramer
            '5bd21420b6428bfb4523562e', // gastrag.ch
            '5a970fea7bb2b5a578812d52', // Randstad
            '59e4b53e7bb2b553412f9be9', // Sv Group
            '5a054fa37bb2b593231413a0', //Migros
            '5dc120e93c050f419712a3b2', // Coople Schweiz
            '5db7f6bb3c050f1326103fc2', // Jobs And More
        ];

        return in_array($org->getId(), $crawlerOrganizations) !== false;
    }
}