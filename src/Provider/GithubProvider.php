<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Provider;

use PersiLiao\GitWebhooks\Entity\Commit;
use PersiLiao\GitWebhooks\Entity\Repository;
use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\Event\PingEvent;
use PersiLiao\GitWebhooks\Event\PushEvent;
use PersiLiao\GitWebhooks\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function hash_hmac;
use function sprintf;
use function strpos;
use function substr;

class GithubProvider extends AbstractProvider
{
    protected $provider = 'GitHub';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->setHeaderSignatureKey('X-Hub-Signature');
    }

    public function create(): AbstractEvent
    {
        $payload = $this->getPayload();
        $event = $this->getRequestEventName();
        switch($event){
            case 'ping':
            {
                return $this->createPingEvent($payload);
            }
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

    protected function createPingEvent(array $payload): PingEvent
    {
        $event = new PingEvent();
        $event->setDescription($payload['repository']['description']);
        $repository = new Repository();
        $repository->setId($payload['repository']['id']);
        $repository->setName($payload['repository']['name']);
        $event->setRepository($repository);
        return $event;
    }

    protected function createPushEvent(array $payload): PushEvent
    {
        // TODO: Implement createPushEvent() method.
    }

    protected function genreateSignature(string $secret, string $payload)
    {
        return hash_hmac('sha1', $payload, $secret, false);
    }

    protected function parseSignature($signature): string
    {
        if(strpos($signature, 'sha1=') !== false){
            return substr($signature, 4);
        }
    }

    protected function createCommit(array $data): Commit
    {
        // TODO: Implement createCommit() method.
    }
}