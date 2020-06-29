<?php

namespace Gastro24\Form\Organizations;

use Core\Form\SummaryForm;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsSocialForm extends SummaryForm
{
    protected $baseFieldset = 'Gastro24\Form\Organizations\OrganizationsSocialFieldset';

    protected $displayMode = self::DISPLAY_SUMMARY;
}