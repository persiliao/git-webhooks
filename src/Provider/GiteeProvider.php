<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Provider;


use Carbon\Carbon;
use PersiLiao\GitWebhooks\Entity\Commit;
use PersiLiao\GitWebhooks\Entity\Repository;
use PersiLiao\GitWebhooks\Entity\User;
use PersiLiao\GitWebhooks\Event\PushEvent;
use PersiLiao\GitWebhooks\Util;
use Symfony\Component\HttpFoundation\Request;
use function error_log;
use function strtolower;

class GiteeProvider extends AbstractProvider
{
    protected $provider = 'Gitee';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->setHeaderSignatureKey('X-Gitee-Token');
    }

    protected function getRequestEventName(): string
    {
        $eventName = strtolower(parent::getRequestEventName());
        if($eventName === 'push hook'){
            $eventName = PushEvent::EVENT_NAME;
        }
        return $eventName;
    }

    protected function genreateSignature(string $secret, string $payload): string
    {
        return $secret;
    }


    protected function createCommit(array $data): Commit
    {
        $commit = new Commit();
        $commit->setId($data['id']);
        $commit->setMessage($data['message']);
        $commit->setDate(new Carbon($data['timestamp']));
        $commit->setModifieds($data['modified'] ?? []);
        $commit->setAddeds($data['added'] ?? []);
        $commit->setRemoveds($data['removed'] ?? []);
        $user = new User();
        $user->setName($data['author']['name']);
        if(isset($data['author']['email']) && !empty($data['author']['email'])){
            $user->setEmail($data['author']['email']);
        }
        $commit->setAuthor($user);
        return $commit;
    }

    protected function createPushEvent(array $payload): PushEvent
    {
        $event = new PushEvent();
        $event->setProvider($this->provider);
        $event->setBefore($payload['before']);
        $event->setAfter($payload['after']);
        $event->setRef($payload['ref']);

        $user = new User();
        $user->setName($payload['pusher']['name']);
        if(isset($payload['pusher']['email']) && !empty($payload['pusher']['email'])){
            $user->setEmail($payload['pusher']['email']);
        }
        $event->setUser($user);

        $repository = new Repository();
        $repository->setName($payload['repository']['name']);
        $repository->setId($payload['repository']['id']);
        $repository->setFullName($payload['repository']['full_name']);
        $repository->setDescription($payload['repository']['description']);
        $repository->setHomepage($payload['repository']['html_url']);
        $repository->setUrl($payload['repository']['clone_url']);
        $event->setRepository($repository);

        $event->setCommits($this->createCommits($payload['commits']));
        $event->setBranchName(Util::getBranchName($payload['ref']));
        return $event;
    }
}