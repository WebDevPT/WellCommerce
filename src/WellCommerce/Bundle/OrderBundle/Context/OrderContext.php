<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\OrderBundle\Context;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\OrderBundle\Calculator\ShippingSubjectInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class OrderAdapter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderContext implements ShippingSubjectInterface
{
    /**
     * @var Order
     */
    protected $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function getQuantity(): int
    {
        return $this->order->getProductTotal()->getQuantity();
    }
    
    public function getWeight(): float
    {
        return $this->order->getProductTotal()->getWeight();
    }
    
    public function getGrossPrice(): float
    {
        return $this->order->getProductTotal()->getGrossPrice();
    }
    
    public function getNetPrice(): float
    {
        return $this->order->getProductTotal()->getNetPrice();
    }
    
    public function getTaxAmount(): float
    {
        return $this->order->getProductTotal()->getTaxAmount();
    }
    
    public function getCurrency(): string
    {
        return $this->order->getCurrency();
    }
    
    public function getCountry(): string
    {
        return $this->order->getShippingAddress()->getCountry();
    }
    
    public function getShop()
    {
        return $this->order->getShop();
    }
    
    public function getSubject()
    {
        return $this->order;
    }
}
