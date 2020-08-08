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
use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\Event\PushEvent;
use PersiLiao\GitWebhooks\Exception\InvalidArgumentException;
use PersiLiao\GitWebhooks\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function sprintf;
use function strtolower;

class GitlabProvider extends AbstractProvider
{
    protected $provider = 'Gitlab';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->setHeaderSignatureKey('X-Gitlab-Token');
    }

    /**
     * @inheritDoc
     */
    public function create(): AbstractEvent
    {
        $payload = $this->getPayload();
        $event = $this->getRequestEventName();
        switch($event){
            case 'push':
            {
                return $this->createPushEvent($payload);
            }
            default:
            {
                throw new InvalidArgumentException(sprintf('%s Git webhook event not support, %s', $this->getProvider(),
                    $event), Response::HTTP_FORBIDDEN);
            }
        }
    }

    protected function getRequestEventName(): string
    {
        $payload = $this->getPayload();
        $eventName = strtolower($payload['event_name']);
        switch($eventName){
            case 'push':
            {
                return 'push';
            }
            default:
            {
                return '';
            }
        }
    }

    protected function createPushEvent(array $payload): PushEvent
    {
        $event = new PushEvent();
        $event->setProvider($this->provider);
        $event->setBefore($payload['before']);
        $event->setAfter($payload['after']);
        $event->setRef($payload['ref']);

        $user = new User();
        $user->setName($payload['user_name']);
        if(isset($payload['user_email']) && !empty($payload['user_email'])){
            $user->setEmail($payload['user_email']);
        }
        $event->setUser($user);

        $repository = new Repository();
        $repository->setName($payload['project']['name']);
        $repository->setId($payload['project']['id']);
        $repository->setFullName($payload['project']['path_with_namespace']);
        $repository->setDescription($payload['project']['description']);
        $repository->setHomepage($payload['project']['homepage']);
        $repository->setUrl($payload['project']['http_url']);
        $event->setRepository($repository);

        $event->setCommits($this->createCommits($payload['commits']));
        $event->setBranchName(Util::getBranchName($payload['ref']));
        return $event;
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
}