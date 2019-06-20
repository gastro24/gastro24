<?php
/**
 * YAWIK
 *
 * @filesource
 * @license    MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */

/** */

namespace Gastro24\Listener;

use Gastro24\Entity\Product\AbstractProduct;
use Gastro24\Entity\UserProduct;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;

/**
 * ${CARET}
 *
 * @todo   write test
 */
class UpdateJobCount
{

    public function __invoke(JobEvent $event)
    {
        /* @var \Auth\Entity\User $owner
         * @var \Gastro24\Entity\UserProduct $productWrapper
         */
        $job     = $event->getJobEntity();
        $company = $job->getOrganization();

        if (!$company) {
            return;
        }

        $owner = $company->getUser();
        if (!$owner) {
            return;
        }

        $productWrapper = $owner->getAttachedEntity(UserProduct::class);
        if (!$productWrapper) {
            return;
        }

        /** @var AbstractProduct $product */
        $product = $productWrapper->getProduct();

        if (Status::ACTIVE == $event->getParam('status')->getName() && Status::INACTIVE == $job->getStatus()) {
            $product->increaseInactiveJobCount();
        }

        if (Status::INACTIVE == $event->getParam('status') && Status::ACTIVE == $job->getStatus()) {
            $product->decreaseInactiveJobCount();
        }
    }
}
