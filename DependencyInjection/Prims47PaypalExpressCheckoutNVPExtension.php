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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class Prims47PaypalExpressCheckoutNVPExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('prims47_paypal_express_checkout_nvp_user',                   $config['user']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_signature',              $config['signature']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_pwd',                    $config['pwd']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_return_route_simple',    $config['return_route_simple']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_return_route_details',   $config['return_route_details']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_cancel_route',           $config['cancel_route']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_route_redirect_success', $config['route_redirect_success']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_costs',                  $config['costs']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_currency_code',          $config['currency_code']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_version',                $config['version']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_is_ssl',                 $config['is_ssl']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_path_ssl',               $config['path_ssl']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_vat_percentage',         $config['vat_percentage']);
        $container->setParameter('prims47_paypal_express_checkout_nvp_is_prod',                $config['is_prod']);

        if (!$config['is_prod']) {
            $container->setParameter('prims47_paypal_express_checkout_nvp_url_api', $config['url_api_dev']);
            $container->setParameter('prims47_paypal_express_checkout_nvp_url_cmd_express_checkout', $config['url_cmd_express_checkout_dev']);
        } else {
            $container->setParameter('prims47_paypal_express_checkout_nvp_url_api', $config['url_api_prod']);
            $container->setParameter('prims47_paypal_express_checkout_nvp_url_cmd_express_checkout', $config['url_cmd_express_checkout_prod']);
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
