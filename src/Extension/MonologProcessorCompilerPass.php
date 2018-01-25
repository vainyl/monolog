<?php
/**
 * Mmjitsu
 *
 * PHP Version 7.1
 *
 * @package   Api
 * @link      https://mmjitsu.com
 */
declare(strict_types=1);

namespace Vainyl\Monolog\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Exception\MissingRequiredServiceException;
use Vainyl\Core\Extension\AbstractCompilerPass;

/**
 * Class MonologProcessorCompilerPass
 *
 * @author  Andrey Dembitskiy <andrew.dembitskiy@gmail.com>
 *
 * @package Vainyl\Monolog\Extension
 */
class MonologProcessorCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('monolog.instance.abstract')) {
            throw new MissingRequiredServiceException($container, 'monolog.instance.abstract');
        }

        $processors = $container->findTaggedServiceIds('logger.processor.monolog');
        foreach ($processors as $processorId => $tags) {
            $loggerDefinition = $container->findDefinition('monolog.instance.abstract');
            $loggerDefinition->addMethodCall('pushProcessor', [new Reference($processorId)]);
        }
    }
}
