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
        $templateValues = $object->getTemplateValues();
        $categories = $templateValues->get('categories');

        $category = $categories[0] ?? null;
        $subCcategory = $categories[1] ?? null;

        /* @var \Jobs\Entity\Job $object */
        return [
            'category' => $category,
            'subcategory' => $subCcategory,
        ];
    }

    public function hydrate(array $data, $object)
    {
        $categories = [];

        /* @var \Jobs\Entity\Job $object */
        if (isset($data['category']) && !empty($data['category'])) {
            $object->getTemplateValues()->category = $data['category'];
            $categories[] = $data['category'];
        }

        if (isset($data['subcategory']) && !empty($data['subcategory']) && $data['subcategory']) {
            $categories[] = $data['subcategory'];
        }
        $object->getTemplateValues()->categories = $categories;

        // reset category
        if (empty($categories)) {
            $object->getTemplateValues()->category = null;
        }

        return $object;
    }
}