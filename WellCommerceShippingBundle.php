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

namespace WellCommerce\Bundle\ShippingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;
use WellCommerce\Bundle\ShippingBundle\DependencyInjection\Compiler;

/**
 * Class WellCommerceShippingBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceShippingBundle extends AbstractWellCommerceBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new Compiler\RegisterShippingMethodCalculatorPass());
        $container->addCompilerPass(new Compiler\RegisterShippingMethodOptionsProviderPass());
    }
}
