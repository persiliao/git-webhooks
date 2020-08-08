<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Provider;

use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\Event\PingEvent;
use PersiLiao\GitWebhooks\Event\PushEvent;

interface ProviderInterface
{
    public function support(): bool;

    /**
     * @return AbstractEvent|PushEvent|PingEvent
     */
    public function create();

    public function validate(AbstractEvent $event, array $secrets = []): bool;
}