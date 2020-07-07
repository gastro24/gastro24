<?php

namespace Gastro24\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;

/**
 * ShowAutomaticJobActivationHint.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ShowAutomaticJobActivationHint extends AbstractHelper
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

        // check for first display and then set to false
        if ($jobActivation->isShowActivationHint()) {
            //show only once
            $jobActivation->setShowActivationHint(false);
            $this->jobActivationRepository->getDocumentManager()->persist($jobActivation);
            $this->jobActivationRepository->getDocumentManager()->flush($jobActivation);

            return true;
        }

        return $jobActivation->isShowActivationHint();
    }

}