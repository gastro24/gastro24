<?php
  
/** */
namespace Gastro24\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * ${CARET}
 * 
 * @todo write test
 */
class LpStellenanzeigen extends AbstractActionController
{

    public function indexAction()
    {
        $this->layout()->setTerminal(true)->setTemplate('gastro24/lp-stellenanzeigen/index');
    }

}
