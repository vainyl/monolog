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
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Exception\MissingRequiredFieldException;
use Vainyl\Core\Exception\MissingRequiredServiceException;
use Vainyl\Core\Extension\AbstractCompilerPass;

/**
 * Class MonologLoggerCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class MonologLoggerCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('monolog.abstract')) {
            throw new MissingRequiredServiceException($container, 'monolog.abstract');
        }

        if (false === $container->hasDefinition('monolog.instance.abstract')) {
            throw new MissingRequiredServiceException($container, 'monolog.instance.abstract');
        }

        $loggers = $container->findTaggedServiceIds('logger');
        foreach ($loggers as $loggerId => $tags) {
            foreach ($tags as $attributes) {
                $loggerDefinition = $container->findDefinition($loggerId);
                if (false === $loggerDefinition->isSynthetic()) {
                    continue;
                }
                if (false === array_key_exists('channel', $attributes)) {
                    throw new MissingRequiredFieldException($container, $loggerId, $attributes, 'channel');
                }
                $channel = $attributes['channel'];

                $monologInstance = new DefinitionDecorator('monolog.instance.abstract');
                $monologInstance->setArguments([$channel]);
                $container->setDefinition('monolog.instance.' . $channel, $monologInstance);

                $newDefinition = new DefinitionDecorator('monolog.abstract');
                $newDefinition->setTags($loggerDefinition->getTags());
                $newDefinition->setArguments([new Reference('monolog.instance.' . $channel)]);
                $container->setDefinition($loggerId, $newDefinition);
            }
        }
    }
}