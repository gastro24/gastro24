<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Listener;

use Core\Form\Event\FormEvent;
use Gastro24\Entity\UserProduct;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class ValidateUserProduct 
{
    public function __invoke(FormEvent $event)
    {
        $company = $event->getForm()->getEntity()->getOrganization();

        if (!$company) {
            return;
        }

        $owner = $company->getUser();

        if (!$owner) {
            return;
        }

        $product = $owner->getAttachedEntity(UserProduct::class);

        if (!$product) {
            return;
        }

        $product = $product->getProduct();

        $snapShot = $event->getForm()->getEntity();
        //$status = ($snapShot->getStatus()) ?$snapShot->getStatus()->getName() : '';
        if (!$product->hasAvailableJobAmount() && !$snapShot->getStatus()) {
            return 'Sie haben bereits alle Ihre Job-Slots verbraucht.';
        }

        if ($product->isExpired()) {
            return 'Ihr Job-Abonnement ist abgelaufen.';
        }
    }
}
