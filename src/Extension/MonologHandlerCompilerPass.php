<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Monolog
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Monolog\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Exception\MissingRequiredServiceException;
use Vainyl\Core\Extension\AbstractCompilerPass;

/**
 * Class MonologHandlerCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class MonologHandlerCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('monolog.formatter')) {
            throw new MissingRequiredServiceException($container, 'monolog.formatter');
        }

        $handlers = $container->findTaggedServiceIds('logger.handler');
        foreach ($handlers as $handlerId => $tags) {
            $loggerDefinition = $container->findDefinition($handlerId);
            $loggerDefinition->addMethodCall('setFormatter', [new Reference('monolog.formatter')]);
        }
    }
}