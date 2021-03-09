<?php

namespace Gastro24\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class LandingPageController extends AbstractActionController
{
    /**
     * @var \Gastro24\Options\Landingpages
     */
    private $landingPageOptions;

    public function __construct($landingPageOptions)
    {
        $this->landingPageOptions = $landingPageOptions;
    }

    public function childsAction()
    {
        /* @var \Laminas\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $parentCategory = $this->params()->fromRoute('parent');
        // load child cats as json
        $childs = $this->landingPageOptions->getChildCategories($parentCategory);
        $results = [];
        foreach ($childs as $childData) {
            $results[$childData['child']] = $childData['spec']['query']['q'];
        }
        ksort($results);

        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
        $response->setContent(json_encode($results));

        return $response;

    }

}