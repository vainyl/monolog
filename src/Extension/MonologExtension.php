<?php
/**
 * Vain Framework
 *
 * PHP Version 7
 *
 * @package   vain-logger
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/allflame/vain-logger
 */
declare(strict_types=1);

namespace Vainyl\Monolog\Extension;

use Vainyl\Core\Extension\AbstractFrameworkExtension;

/**
 * Class MonologExtension
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class MonologExtension extends AbstractFrameworkExtension
{
    /**
     * @inheritDoc
     */
    public function getCompilerPasses(): array
    {
        return [new MonologLoggerCompilerPass(), new MonologHandlerCompilerPass()];
    }
}