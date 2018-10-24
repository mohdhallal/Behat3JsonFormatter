<?php
/**
 * @author George Tarkalanov, <g.turkalanov@gmail.com>
 */

namespace gturkalanov\Behat3JsonExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

class Behat3JsonExtension implements Extension
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'jsonformatter';
    }
    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition("gturkalanov\\Behat3JsonExtension\\Formatter\\Formatter");
        $definition->addArgument($config['prettify']);
        $definition->addArgument($config['file_name']);
        $definition->addArgument($config['path']);
        $definition->addArgument($container->get('cli.output'));
        $container->setDefinition("json.formatter", $definition)
            ->addTag("output.formatter");

    }
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode("prettify")->defaultValue("false");
        $builder->children()->scalarNode("file_name")->defaultValue("false");
        $builder->children()->scalarNode("path")->defaultValue("build/json_results");

    }
}
