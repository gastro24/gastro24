<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Validator;

use Interop\Container\ContainerInterface;
use Laminas\Validator\AbstractValidator;
use Laminas\Validator\ValidatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class IframeEmbeddableUri extends AbstractValidator
{
    const NOT_EMBEDDABLE = 'NOT_EMBEDDABLE';

    /**
     * @var string
     */
    protected $basePath;

    protected $messageTemplates = [
        self::NOT_EMBEDDABLE => 'URI "%value%" is not embeddable in an iframe.',
    ];

    protected $messageVariables = [
        'match' => 'match'
    ];

    protected $options = [
        'invalidUriStart' => [
            //'https://www.adecco.ch/',
            'https://jobs.mcdonalds.ch',
            'https://www.gilde.ch/',
            'https://jobs.coopjobs.ch',
        ],
    ];

    protected $match = null;

    public function isValid($value)
    {
        $this->setValue($value);

        foreach ($this->options['invalidUriStart'] as $str) {
            if (0 === strpos($value, $str)) {

                // allow embed for uploaded PDF files
                if (0 === strpos($this->getPublicJobPdfPath(), $str)) {
                    return true;
                }

                $this->match = $str;
                $this->error(self::NOT_EMBEDDABLE);
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getInvalidUris()
    {
        return $this->options['invalidUriStart'];
    }

    private function getPublicJobPdfPath()
    {
        return $this->getBasePath() . '/static/Jobs';
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     *
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }


}
