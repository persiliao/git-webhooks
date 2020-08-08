<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Provider;

use Closure;
use PersiLiao\GitWebhooks\Entity\Commit;
use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\Event\PushEvent;
use PersiLiao\GitWebhooks\EventHandlerInterface;
use PersiLiao\GitWebhooks\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function array_key_exists;
use function array_merge;
use function call_user_func;
use function is_string;
use function json_decode;
use function sprintf;
use function strtolower;

abstract class AbstractProvider implements ProviderInterface, EventHandlerInterface
{
    protected $defaultEvents = [
        'push' => 'onPush',
        'ping' => 'onPing'
    ];

    /**
     * @var string[]
     */
    protected $events = [];

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
    protected $secret;

    /**
     * @var string
     */
    protected $payloadRaw;

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
    public function __construct(Request $request)
    {
        $this->request = $request;
        $provider = $this->getProvider();
        $this->headerEventKey = sprintf('X-%s-Event', $provider);
        $this->headerSignatureKey = sprintf('X-%s-Signature', $provider);
        $this->setPayloadRaw();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        if(empty($this->provider) || !is_string($this->provider)){
            throw new InvalidArgumentException(sprintf('%s $provider must be a string', static::class), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->provider;
    }

    public function support(): bool
    {
        return $this->isPost() && $this->isJson() && $this->request->headers->has($this->getHeaderEventKey())
            && $this->request->headers->has($this->getHeaderSignatureKey());
    }

    protected function isPost(): bool
    {
        $method = $this->request->server->get('REQUEST_METHOD');
        if($method !== Request::METHOD_POST){
            throw new InvalidArgumentException(sprintf('%s Request method %s not support, Only supports POST', $this->getProvider
            (), $method), Response::HTTP_FORBIDDEN);
        }
        return true;
    }

    protected function isJson()
    {
        $contentType = $this->request->getContentType();
        if($contentType !== 'json'){
            throw new InvalidArgumentException(sprintf('%s Request content type not support, %s', $this->getProvider
            (), $contentType));
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
     * @param string $headerEventKey
     * @return AbstractProvider
     */
    public function setHeaderEventKey(string $headerEventKey): AbstractProvider
    {
        $this->headerEventKey = $headerEventKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderSignatureKey(): string
    {
        return $this->headerSignatureKey;
    }

    /**
     * @param string $headerSignatureKey
     * @return AbstractProvider
     */
    public function setHeaderSignatureKey(string $headerSignatureKey): AbstractProvider
    {
        $this->headerSignatureKey = $headerSignatureKey;
        return $this;
    }

    public function validate(AbstractEvent $event, array $secrets = []): bool
    {
        if(empty($this->getSignature())){
            return true;
        }

        if(!empty($secrets)){
            $repositoryName = $event->getRepository()->getName();
            if(!empty($repositoryName) && array_key_exists($repositoryName, $secrets)){
                $this->setSecret($secrets[$repositoryName]);
            }
        }

        if(empty($this->getSecret())){
            throw new InvalidArgumentException(sprintf('%s Signature check error', $this->getProvider()),
                Response::HTTP_UNAUTHORIZED);
        }

        if($this->genreateSignature($this->secret, $this->getPayloadRaw()) !== $this->getSignature()){
            throw new InvalidArgumentException(sprintf('%s Signature check error', $this->getProvider()),
                Response::HTTP_UNAUTHORIZED);
        }
        return true;
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
        return $this->parseSignature($signature);
    }

    protected function parseSignature($signature): string
    {
        return $signature;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret ?: '';
    }

    /**
     * @param string $secret
     * @return AbstractProvider
     */
    public function setSecret(string $secret): AbstractProvider
    {
        $this->secret = $secret;
        return $this;
    }

    protected function genreateSignature(string $secret, string $payload): string
    {
        return hash_hmac('sha256', $payload, $secret, false);
    }

    protected function getPayloadRaw()
    {
        return $this->payloadRaw;
    }

    /**
     * @param string $payload
     * @return AbstractProvider
     */
    protected function setPayloadRaw(): AbstractProvider
    {
        $this->payloadRaw = $this->request->getContent();
        return $this;
    }

    public function addHandle(string $eventName, Closure $closure)
    {
        $requestEventName = $this->getRequestEventName();
        $events = array_merge($this->defaultEvents, $this->events);
        if(!isset($events[$requestEventName]) || empty($events[$requestEventName])){
            throw new InvalidArgumentException(sprintf('%s Request Event not support, %s', $this->getProvider(),
                $requestEventName), Response::HTTP_BAD_REQUEST);
        }
        if($events[$requestEventName] === $eventName){
            return call_user_func($closure);
        }
    }

    /**
     * @return string
     */
    protected function getRequestEventName(): string
    {
        return strtolower($this->request->headers->get($this->getHeaderEventKey()) ?: '');
    }

    protected function getPayload(): array
    {
        return json_decode($this->getPayloadRaw(), true);
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