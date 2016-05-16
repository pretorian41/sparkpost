<?php

namespace Braem\SparkpostMailerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BraemSparkpostMailerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!isset($config['api_key'])) {
            throw new \Exception('api_key needed for connection ');
        }
        else {
            $container
                ->register('hip_mandrill.dispatcher', isset($config['mailer']) ? $config['mailer']: '%braem.spark_post.mailer.default.class%')
                ->addMethodCall('setApiKey', array($config['api_key']))
                ->addMethodCall('setSparkPostUri', array($config['api_uri']))
                ->addMethodCall('setFromEmail', array($config['from_email']))
                ->addMethodCall('setFromName', array($config['company_name']))
                ->addMethodCall('setEmailForTestEnv', array($config['email_for_test_env']))
                ->addMethodCall('setEnvironment', array($config['environment']))
            ;
            // we need this 'ims.car.isshipped.now.listener' to be triggered before 'ims.rest.api.curl.listener.service', so order here is important
            // seems they are triggering from the end to top.
//            $container
//                ->register('ims.car.isshipped.now.listener', '%ims.car.isshipped.now.listener.class%')
//                ->addTag('doctrine.event_subscriber', array('connection' => 'default', 'priority' => 19))
//                ->addArgument(new Reference('service_container'))
            ;
        }

    }
}
