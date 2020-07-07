<?php

namespace Gastro24\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;

/**
 * JobDraftsCount.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobDraftsCount extends AbstractHelper
{
    private $jobsRepository;

    public function __construct($jobsRepository)
    {
        $this->jobsRepository = $jobsRepository;
    }

    /**
     * @param $user
     * @return int
     */
    public function __invoke($user)
    {
        return (int)$this->jobsRepository->findBy(['user' => $user, 'isDraft' => true]);
    }
}