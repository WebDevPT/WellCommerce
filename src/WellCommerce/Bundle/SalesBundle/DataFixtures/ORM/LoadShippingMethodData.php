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

namespace WellCommerce\Bundle\SalesBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CommonBundle\DataFixtures\ORM\LoadCurrencyData;
use WellCommerce\Bundle\CommonBundle\DataFixtures\ORM\LoadTaxData;
use WellCommerce\Bundle\CoreBundle\DataFixtures\AbstractDataFixture;
use WellCommerce\Bundle\CoreBundle\Entity\Price;
use WellCommerce\Bundle\SalesBundle\Entity\ShippingMethodCost;
use WellCommerce\Bundle\SalesBundle\Entity\ShippingMethodInterface;

/**
 * Class LoadShippingData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadShippingMethodData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tax             = $this->randomizeSamples('tax', LoadTaxData::$samples);
        $currency        = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        $factory         = $this->container->get('shipping_method.factory');

        $fedEx = $factory->create();
        $fedEx->setCalculator('price_table');
        $fedEx->setTax($tax);
        $fedEx->setCurrency($currency);
        $fedEx->translate('en')->setName('FedEx');
        $fedEx->mergeNewTranslations();
        $fedEx->setCosts($this->getShippingCostsCollection($fedEx));
        $manager->persist($fedEx);

        $ups = $factory->create();
        $ups->setCalculator('price_table');
        $ups->setTax($tax);
        $ups->setCurrency($currency);
        $ups->translate('en')->setName('UPS');
        $ups->mergeNewTranslations();
        $ups->setCosts($this->getShippingCostsCollection($ups));
        $manager->persist($ups);

        $manager->flush();

        $this->setReference('shipping_method_fedex', $fedEx);
        $this->setReference('shipping_method_ups', $ups);
    }

    protected function getShippingCostsCollection(ShippingMethodInterface $shippingMethod)
    {
        $collection = new ArrayCollection();

        $cost = new ShippingMethodCost();
        $cost->setRangeFrom(0);
        $cost->setRangeTo(100000);

        $price = new Price();
        $price->setCurrency('EUR');
        $price->setNetAmount(10);
        $price->setTaxAmount(2.3);
        $price->setTaxRate(23);
        $price->setGrossAmount(12.3);

        $cost->setCost($price);
        $cost->setShippingMethod($shippingMethod);
        $collection->add($cost);

        return $collection;
    }
}
