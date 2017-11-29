<?php

namespace gturkalanov\Behat3JsonFormatter;

use Behat\EnvironmentLoader;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class FormatterExtension implements ExtensionInterface
{
    /**
     * @return string
     */
    public function getConfigKey()
    {
        return 'behat3jsonformatter';
    }

    /**
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new EnvironmentLoader($this, $container, $config);
        $loader->load();
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }



    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {

    }


}
