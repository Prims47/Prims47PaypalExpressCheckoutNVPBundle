<?php

/*
 * This file is part of the Prims47 package.
 *
 * (c) Ilan Benhamou <ilan.primsx@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prims47\Bundle\PaypalExpressCheckoutNVPBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prims47_paypal_express_checkout_nvp');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
            ->scalarNode('user')->isRequired()->end()
            ->scalarNode('signature')->isRequired()->end()
            ->scalarNode('pwd')->isRequired()->end()
            ->scalarNode('cancel_route')->isRequired()->end()
            ->scalarNode('route_redirect_success')->isRequired()->end()
            ->scalarNode('return_route_simple')->defaultValue('prims47_paypal_express_checkout_nvp_return_api_simple')->end()
            ->scalarNode('return_route_details')->defaultValue('prims47_paypal_express_checkout_nvp_return_api_details')->end()
            ->scalarNode('costs')->defaultValue('0')->end()
            ->scalarNode('currency_code')->defaultValue('EUR')->end()
            ->scalarNode('version')->defaultValue('111')->end()
            ->scalarNode('is_ssl')->defaultFalse()->end()
            ->scalarNode('path_ssl')->defaultNull()->end()
            ->scalarNode('vat_percentage')->defaultValue('0')->end()
            ->scalarNode('is_prod')->defaultFalse()->end()
            ->scalarNode('url_api_dev')->defaultValue('https://api-3t.sandbox.paypal.com/nvp')->end()
            ->scalarNode('url_api_prod')->defaultValue('https://api-3t.paypal.com/nvp')->end()
            ->scalarNode('url_cmd_express_checkout_dev')->defaultValue('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit')->end()
            ->scalarNode('url_cmd_express_checkout_prod')->defaultValue('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit')->end()
            ->end();

        return $treeBuilder;
    }
}
