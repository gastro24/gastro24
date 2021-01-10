<?php

namespace Gastro24\Form\Jobs;

use Core\Form\SummaryForm;

class CategoryForm extends SummaryForm
{
    protected $baseFieldset = 'Gastro24\Form\Jobs\CategoriesFieldset';

    protected $label = /*@translate*/ 'Kategorie';
}