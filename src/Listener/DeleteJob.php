<?php

namespace Gastro24\Listener;

use Auth\Entity\UserInterface;
use Core\Listener\Events\AjaxEvent;
use Gastro24\Entity\Product\AbstractProduct;
use Gastro24\Entity\UserProduct;
use Jobs\Repository\Job;
use Laminas\Permissions\Acl\AclInterface;

/**
 * DeleteJob.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class DeleteJob
{
    /**
     * Job repository
     *
     * @var \Jobs\Repository\Job
     */
    private $repository;

    /**
     * Current user
     *
     * @var \Auth\Entity\UserInterface
     */
    private $user;

    /**
     * ACL service
     *
     * @var \Laminas\Permissions\Acl\AclInterface
     */
    private $acl;

    /**
     * @param Job           $repository
     * @param UserInterface $user
     * @param AclInterface  $acl
     */
    public function __construct(Job $repository, UserInterface $user, AclInterface $acl)
    {
        $this->repository = $repository;
        $this->user       = $user;
        $this->acl        = $acl;
    }

    /**
     * Delete a job via ajax call.
     *
     * Returns an array with two or three keys:
     * - "success": a boolean flag whether the call was successful or not.
     * - "status": a text flag. "fail" or "OK"
     * - "error": An error message.
     *
     * @param AjaxEvent $event
     *
     * @return array
     */
    public function __invoke(AjaxEvent $event)
    {
        $request = $event->getRequest();
        $query   = $request->getQuery();
        $id      = $query->get('id');

        if (!$id) {
            return ['success' => false, 'status' => 'fail', 'error' => 'No id provided'];
        }

        $job = $this->repository->find($id);

        if (!$job || !$this->acl->isAllowed($this->user, $job, 'delete')) {
            return ['success' => false, 'status' => 'fail', 'error' => !$job ? 'Job not found.' : 'No permissions.'];
        }

        $job->delete();

        /* update job count */
        $company = $job->getOrganization();
        if (!$company) {
            return ['success' => true, 'status' => 'OK'];
        }

        $owner = $company->getUser();
        if (!$owner) {
            return ['success' => true, 'status' => 'OK'];
        }
        $productWrapper = $owner->getAttachedEntity(UserProduct::class);
        if (!$productWrapper) {
            return ['success' => true, 'status' => 'OK'];
        }

        /** @var AbstractProduct $product */
        $product = $productWrapper->getProduct();
        $product->decreaseJobCount();

        return ['success' => true, 'status' => 'OK'];
    }

}