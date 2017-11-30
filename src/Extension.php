<?php

namespace gturkalanov\Behat3JsonFormatter;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class Behat3JsonFormatter implements ExtensionInterface
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
        $definition = new Definition('Vanare\\BehatCucumberJsonFormatter\\Formatter\\Formatter');

        $definition->addArgument($config['filename']);
        $definition->addArgument($config['outputDir']);

        $container
            ->setDefinition('json.formatter', $definition)
            ->addTag('output.formatter')
        ;
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
        $builder->children()->scalarNode('filename')->defaultValue('report.json');
        $builder->children()->scalarNode('outputDir')->defaultValue('build/tests');

    }


}
