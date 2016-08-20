<?php

namespace WellCommerce\Bundle\ShippingBundle\Entity;

use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CoreBundle\Behaviours\Timestampable\TimestampableTrait;
use WellCommerce\Bundle\CoreBundle\Entity\IdentifiableTrait;

/**
 * Class ShippingMethodCost
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethodCost implements ShippingMethodCostInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use ShippingMethodAwareTrait;

    /**
     * @var float
     */
    protected $rangeFrom;

    /**
     * @var float
     */
    protected $rangeTo;

    /**
     * @var Price
     */
    protected $cost;

    /**
     * {@inheritdoc}
     */
    public function getRangeFrom() : float
    {
        return $this->rangeFrom;
    }

    /**
     * {@inheritdoc}
     */
    public function setRangeFrom(float $rangeFrom)
    {
        $this->rangeFrom = (float)$rangeFrom;
    }

    /**
     * {@inheritdoc}
     */
    public function getRangeTo() : float
    {
        return $this->rangeTo;
    }

    /**
     * {@inheritdoc}
     */
    public function setRangeTo(float $rangeTo)
    {
        $this->rangeTo = (float)$rangeTo;
    }

    /**
     * @return Price
     */
    public function getCost() : Price
    {
        return $this->cost;
    }

    /**
     * @param Price $cost
     */
    public function setCost(Price $cost)
    {
        $this->cost = $cost;
    }
}
