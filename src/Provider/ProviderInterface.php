<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use PersiLiao\Event\PingEvent;
use PersiLiao\Event\PushEvent;
use PersiLiao\GitWebhooks\Event\AbstractEvent;

interface ProviderInterface
{
    public function support(): bool;

    /**
     * @return AbstractEvent|PushEvent|PingEvent
     */
    public function create();

    public function validate(string $secret): bool;
}