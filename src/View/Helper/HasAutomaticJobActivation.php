<?php

namespace Gastro24\View\Helper;

use Gastro24\Validator\IframeEmbeddableUri;
use Laminas\Form\View\Helper\AbstractHelper;

/**
 * HasAutomaticJobActivation.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class HasAutomaticJobActivation extends AbstractHelper
{
    private $jobActivationRepository;

    public function __construct($jobActivationRepository)
    {
        $this->jobActivationRepository = $jobActivationRepository;
    }

    /**
     * @param $jobLink
     * @return bool
     */
    public function __invoke($user)
    {
        /** @var \Gastro24\Entity\JobActivation $jobActivation */
        $jobActivation = $this->jobActivationRepository->findOneByUserId($user->getId());

        if (!$jobActivation) {
            return false;
        }

        return $jobActivation->isAutomaticJobActivation();
    }
}