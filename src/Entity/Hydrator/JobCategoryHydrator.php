<?php

namespace Gastro24\Entity\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Laminas\Hydrator\HydratorInterface;

/**
 * Class JobCategoryHydrator
 * @package Gastro24\Entity\Hydrator
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobCategoryHydrator implements HydratorInterface
{
    public function extract($object)
    {
        /* @var \Jobs\Entity\Job $object */
        return [
            'category' => $object->getTemplateValues()->get('category'),
        ];
    }

    public function hydrate(array $data, $object)
    {
        /* @var \Jobs\Entity\Job $object */
        $object->getTemplateValues()->category = $data['category'];

        return $object;
    }
}