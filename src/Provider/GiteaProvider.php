<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use PersiLiao\Entity\Commit;
use PersiLiao\Entity\User;
use PersiLiao\Event\PushEvent;
use PersiLiao\Exception\InvalidArgumentException;
use PersiLiao\Provider\AbstractProvider;

class GiteaProvider extends AbstractProvider
{
    protected $provider = 'Gitea';

    public function create()
    {
        $payload = $this->getPayloadData();
        switch($this->request->headers->get($this->getHeaderEvent())){
            case 'push':
            {
                return $this->createPushEvent($payload);
            }
            default:
            {
                throw new InvalidArgumentException('Git webhook event not support');
            }
        }
    }

    private function createPushEvent(array $payload)
    {
        $event = new PushEvent();
        $event->setProvider($this->provider);
        $event->setBefore($payload['before']);
        $event->setAfter($payload['after']);
        $event->setRef($payload['ref']);

        $user = new User();
        $user->setId($payload['pusher']['id']);
        $user->setName($payload['pusher']['full_name']);
        if(isset($payload['pusher']['email']) && !empty($payload['pusher']['email'])){
            $user->setEmail($payload['pusher']['email']);
        }
        $event->setUser($user);
        $event->setCommits($this->createCommits($payload['commits']));
        return $event;
    }

    protected function createCommit(array $data)
    {
        $commit = new Commit();
        $commit->setId($data['id']);
        $commit->setMessage($data['message']);
        //$commit->setDate();
    }
}