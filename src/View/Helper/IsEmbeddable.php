<?php

namespace Gastro24\View\Helper;

use Gastro24\Validator\IframeEmbeddableUri;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * IsEmbeddable.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class IsEmbeddable extends AbstractHelper
{
    /**
     * @var IframeEmbeddableUri
     */
    private $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $jobLink
     * @return bool
     */
    public function __invoke($jobLink)
    {
        return $this->validator->isValid($jobLink);
    }
}