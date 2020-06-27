<?php

namespace Gastro24\Form\Organizations;

use Core\Form\SummaryForm;

class OrganizationsDescriptionForm extends SummaryForm
{
    protected $baseFieldset = 'Gastro24\Form\Organizations\OrganizationsDescriptionFieldset';

    protected $displayMode = self::DISPLAY_SUMMARY;
}
