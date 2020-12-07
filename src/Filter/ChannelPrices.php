<?php

namespace Gastro24\Filter;

use Jobs\Options\ChannelOptions;
use Jobs\Options\ProviderOptions;
use Laminas\Filter\Exception;
use Laminas\Filter\FilterInterface;

/**
 * Class ChannelPrices
 * @package Gastro24\Filter
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ChannelPrices implements FilterInterface
{
    protected $providers;

    public function __construct(ProviderOptions $providers)
    {
        $this->providers = $providers;
    }
    /**
     * This filter allows you to loop over the selected Channels. Each channel can have three
     * prices 'min', 'base', 'list'. The default calculation simply adds a discount of 13,5% if
     * more than one channel is selected.
     *
     * In addition, you'll get a special discount of 100 whatever, if your job will be posted on
     * jobs.yawik.org :-)
     *
     * Returns the result of filtering $value
     *
     * @param  array $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value = [])
    {
        $sum = 0;
        $amount = 0;
        $absoluteDiscount = 0;
        if (empty($value)) {
            return 0;
        }

        foreach ($value as $channelKey) {
            /* @var $channel ChannelOptions */
            $channel = $this->providers->getChannel($channelKey);
            if ('yawik' == $channelKey) {
                $absoluteDiscount = 100;
            }
            if ($channel instanceof ChannelOptions && $channel->getPrice('base')>0) {
                $sum += $channel->getPrice('base');
                $amount++;
            }
        }
        // @see https://gitlab.cross-solution.de/YAWIK/Gastro24/-/issues/596#note_39724
        // remove hardcoded discount
//        $discount=1-($amount-1)*13.5/100;
//        if ($discount>0) {
//            $sum= round($sum * $discount, 2);
//        }
        return $sum-$absoluteDiscount;
    }
}
