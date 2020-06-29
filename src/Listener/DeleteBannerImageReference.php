<?php

namespace Gastro24\Listener;

use Core\Listener\Events\FileEvent;
use Gastro24\Entity\TemplateImage;

/**
 * DeleteBannerImageReference.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class DeleteBannerImageReference
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FileEvent $event)
    {
        $file = $event->getFile();

        if (! $file instanceOf TemplateImage) {
            return;
        }

        $imageId = new \MongoId($file->getId());

        foreach ($this->repository->findBy(['banner' => $imageId]) as $organizationAdditional) {
            /* @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
            $organizationAdditional->clearBanner();
        }
    }

}