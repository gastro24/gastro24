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

use Jobs\Entity\JobInterface;
use Laminas\View\Helper\AbstractHelper;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobboardApplyUrl extends AbstractHelper
{
    private $urlHelper;

    public function __construct($urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function __invoke(JobInterface $job)
    {
        $ats = $job->getAtsMode();

        if ($ats->isDisabled()) {
            $url = $job->getLink();
            $pdflink = null;
            $class = "no-apply-link";
            if (strpos($url,"http") !== false) {
               $text = '.pdf' == substr($url, -4) ? 'Originalinserat öffnen' : 'Jetzt online bewerben';
            } else {
                $url = null;
            }
        } else if ($ats->isIntern() || $ats->isEmail()) {

            $route = 'lang/apply';
            $params = [
                'applyId' => $job->getApplyId(),
                'lang' => 'de',
            ];

            $url  = $this->urlHelper->__invoke($route, $params);
            $class = 'internal-apply-link';
            $text = 'Jetzt online bewerben';
            //$pdflink = '.pdf' == substr($job->getLink(), -4) ?$job->getLink() : null;

        } else {
            $url = $ats->getUri();
            $class = 'external-apply-link';
            $text = 'Jetzt online bewerben';
            //$pdflink = '.pdf' == substr($job->getLink(), -4) ?$job->getLink() : null;
        }

        if ($pdflink) {
         //   $pdflink = ' <a href="' . $pdflink . '" class="btn btn-primary">PDF downloaden</a>';
        }

        return $url?sprintf('<a rel="nofollow" href="%s" class="btn btn-primary %s">%s &nbsp;<svg fill="#ffffff" viewBox="0 0 20 20" width="12px" height="12px"><path d="M5.408.153a.588.588 0 00-.098.755l.059.076L13.566 10 5.37 19.016a.588.588 0 00-.025.761l.065.07c.216.197.54.202.761.026l.07-.066 8.27-9.096c.337-.372.363-.925.077-1.324l-.078-.097L6.24.193a.588.588 0 00-.832-.04z" fill-rule="evenodd"></path></svg></a>%s', $url, $class, $text, $pdflink):'';
    }
}
