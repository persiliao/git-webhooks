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
use Symfony\Component\HttpFoundation\Request;

class GogsProvider extends GiteaProvider
{
    protected $provider = 'Gogs';
}