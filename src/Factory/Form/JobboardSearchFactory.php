<?php
namespace Gastro24\Factory\Form;

use Gastro24\Form\JobboardSearch;
use \Jobs\Factory\Form\JobboardSearchFactory as BaseFactory;

/**
 * Class JobboardSearchFactory
 * @package Gastro24\Factory\Form
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobboardSearchFactory extends BaseFactory
{
    const OPTIONS_NAME = 'Jobs/JobboardSearchOptions';

    const CLASS_NAME = JobboardSearch::class;
}
