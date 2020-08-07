<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\Provider;

use Closure;
use PersiLiao\Entity\Commit;
use PersiLiao\Event\PushEvent;
use PersiLiao\Exception\InvalidArgumentException;
use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\EventHandlerInterface;
use PersiLiao\GitWebhooks\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use function call_user_func;
use function error_log;
use function is_string;
use function json_decode;
use function sprintf;

abstract class AbstractProvider implements ProviderInterface, EventHandlerInterface
{
    /**
     * @var string[]
     */
    protected $events;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $payload;

    /**
     * @var string
     */
    protected $headerEventKey;

    /**
     * @var string
     */
    protected $headerSignatureKey;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * AbstractProvider constructor.
     * @param Request $request
     */
    final public function __construct(Request $request)
    {
        $this->request = $request;
        $provider = $this->getProvider();
        $this->headerEventKey = sprintf('X-%s-Event', $provider);
        $this->headerSignatureKey = sprintf('X-%s-Signature', $provider);
        $this->setPayload();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        if(empty($this->provider) || !is_string($this->provider)){
            throw new InvalidArgumentException(sprintf('%s $provider must be a string', static::class));
        }
        return $this->provider;
    }

    public function support(): bool
    {
        return $this->isJson() && $this->request->headers->has($this->getHeaderEventKey())
            && $this->request->headers->has($this->getHeaderSignatureKey());
    }

    protected function isJson()
    {
        $contentType = $this->request->getContentType();
        if($contentType !== 'application/json'){
            error_log(sprintf('%s Request content type %s not support', $this->getProvider(), $contentType));
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getHeaderEventKey(): string
    {
        return $this->headerEventKey;
    }

    /**
     * @return string
     */
    public function getHeaderSignatureKey(): string
    {
        return $this->headerSignatureKey;
    }

    public function validate(string $secret): bool
    {
        if($this->genreateSignature($secret, $this->getPayload()) !== $this->getSignature()){
            error_log(sprintf('%s Signature check error', $this->getProvider()));
        }
        return true;
    }

    protected function genreateSignature(string $secret, string $payload)
    {
        return hash_hmac('sha256', $payload, $secret, false);
    }

    protected function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     * @return AbstractProvider
     */
    protected function setPayload(): AbstractProvider
    {
        $this->payload = $this->request->getContent();
        return $this;
    }

    protected function getSignature(): string
    {
        $signatureKey = $this->getHeaderSignatureKey();
        if($this->request->headers->has($signatureKey) === false){
            return '';
        }
        $signature = $this->request->headers->get($signatureKey);
        if(empty($signature)){
            return '';
        }
        return $signature;
    }

    public function addHandle(string $eventName, Closure $closure, AbstractEvent $event)
    {
        $requestEventName = $this->getEventName();
        if(isset($this->events[$requestEventName]) && $this->events[$requestEventName] === $eventName){
            return call_user_func($closure, $event);
        }
        throw new InvalidArgumentException(sprintf('%s Request Event not support', $this->getProvider()));
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->request->headers->get($this->headerEventKey);
    }

    protected function getPayloadData(): array
    {
        return json_decode($this->getPayload(), true);
    }

    /**
     * @param array $data
     * @return Commit[]
     */
    protected function createCommits(array $data)
    {
        $result = [];

        foreach($data as $row){
            $result[] = $this->createCommit($row);
        }

        return $result;
    }

    abstract protected function createCommit(array $data): Commit;

    abstract protected function createPushEvent(array $payload): PushEvent;
}