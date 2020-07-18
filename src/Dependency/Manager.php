<?php
/**
 * Yawik DemoSkin
 */

/** */
namespace Gastro24\Dependency;

use Auth\Dependency\Manager as AuthDependencyManager;
use Auth\Entity\UserInterface;

/**
 *
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 */
class Manager extends AuthDependencyManager
{

    /**
     * @inheritDoc
     */
    public function removeItems(UserInterface $user)
    {
        // removal of standard users are not allowed in the demo
        return
            in_array($user->getLogin(), ['admin']) ? false : parent::removeItems($user);
    }
}
