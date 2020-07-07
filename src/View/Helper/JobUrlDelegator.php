<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\View\Helper;

use Jobs\Entity\JobInterface as Job;
use Jobs\View\Helper\JobUrl;
use Laminas\View\Helper\AbstractHelper;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobUrlDelegator extends AbstractHelper
{

    private $jobUrlHelper;
    private $urlHelper;
    private $serverUrlHelper;

    public function __construct(JobUrl $jobUrlHelper, $urlHelper, $serverUrlHelper)
    {
        $this->jobUrlHelper = $jobUrlHelper;
        $this->urlHelper = $urlHelper;
        $this->serverUrlHelper = $serverUrlHelper;
    }

    public function __invoke(Job $jobEntity, $options = [], $urlParams = [])
    {
        if (!isset($urlParams['id'])) {
            $urlParams['title'] = $this->formatTitle($jobEntity->getTitle());
            $urlParams['id'] = $jobEntity->getId();
        }

        if (/*$jobEntity->getLink() && */ (!isset($options['preview']) || false === $options['preview'])) {
            $url = $this->urlHelper->__invoke('lang/job-view-extern', $urlParams, true);
            /* Unfortunately, we need to copy this portion from the jobUrlHelper
             * but simplified, as some options are implied */

            if (isset($options['linkOnly']) && $options['linkOnly']){
                if (isset($options['absolute']) && $options['absolute']) {
                    $url = $this->serverUrlHelper->__invoke($url);
                }
                $result = $url;
            }else{
                $result = sprintf('<a href="%s">%s</a>',
                    $url,
                    strip_tags($jobEntity->getTitle()));
            }
            return $result;
        }

        $link = $this->jobUrlHelper->__invoke($jobEntity, $options, $urlParams);

        if (false !== strpos($link, '.html')) {
            if (isset($options['linkOnly']) && $options['linkOnly']) {
                $link = preg_replace('~\?.*$~', '', $link);
            } else {
                $link = preg_replace('~\?[^"\']*~', '', $link);
            }
        }

        return $link;
    }

    private function formatTitle($title)
    {
        $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
        $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
        $title = str_replace($search, $replace, $title);
        $title = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $title));

        return $title;
    }


    /* Proxy methods */

    public function setUrlHelper($helper)
    {
        return $this->jobUrlHelper->setUrlHelper($helper);
    }

    public function setParamsHelper($helper)
    {
        return $this->jobUrlHelper->setParamsHelper($helper);
    }

    public function setServerUrlHelper($helper)
    {
        return $this->jobUrlHelper->setServerUrlHelper($helper);
    }

    public function setOptions($options)
    {
        return $this->jobUrlHelper->setOptions($options);
    }
}
