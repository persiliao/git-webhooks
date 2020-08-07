<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Provider;

use PersiLiao\GitWebhooks\Entity\Commit;
use PersiLiao\GitWebhooks\Event\PushEvent;

class GithubProvider extends AbstractProvider
{
    protected $provider = 'GitHub';

    public function create()
    {
        // TODO: Implement create() method.
    }

    protected function createPushEvent(array $payload): PushEvent
    {
        // TODO: Implement createPushEvent() method.
    }

    protected function createCommit(array $data): Commit
    {
        // TODO: Implement createCommit() method.
    }
}