<?php
namespace Gastro24\Factory\Form\Jobs;

use Gastro24\Form\Jobs\BaseFieldset;
use Jobs\Factory\Form\BaseFieldsetFactory as CoreFactory;

/**
 * Factory for the BaseFieldset (Job Title and Location)
 */
class BaseFieldsetFactory extends CoreFactory
{
    const CLASS_NAME = BaseFieldset::class;
}