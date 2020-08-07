<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\Provider\AbstractProvider;

class GithubProvider extends AbstractProvider
{
    protected $provider = 'GitHub';

    public function create()
    {
        // TODO: Implement create() method.
    }

    protected function createCommit(array $data)
    {
        // TODO: Implement createCommit() method.
    }
}