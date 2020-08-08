<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use Closure;

interface EventHandlerInterface
{
    public function addHandle(string $eventName, Closure $closure);
}