<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\Provider;

use PersiLiao\Entity\Commit;
use PersiLiao\Exception\InvalidArgumentException;
use PersiLiao\GitWebhooks\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;
use function sprintf;

abstract class AbstractProvider implements ProviderInterface
{
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
    protected $headerEvent;

    /**
     * @var string
     */
    protected $headerSignature;

    /**
     * AbstractProvider constructor.
     * @param Request $request
     */
    final public function __construct(Request $request)
    {
        $this->request = $request;
        $provider = $this->getProvider();
        $this->headerEvent = sprintf('X-%s-Event', $provider);
        $this->headerSignature = sprintf('X-%s-Signature', $provider);
        $this->setPayload();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        if(empty($this->provider)){
            throw new InvalidArgumentException('Provider must be a string');
        }
        return $this->provider;
    }

    public function support(): bool
    {
        return $this->isJson() && $this->request->headers->has($this->getHeaderEvent());
    }

    protected function isJson()
    {
        if($this->request->getContentType() !== 'application/json'){
            throw new InvalidArgumentException('Request content type not support');
        }
        return true;
    }

    /**
     * @return string
     */
    public function getHeaderEvent(): string
    {
        return $this->headerEvent;
    }

    public function validate(string $secret): bool
    {
        if($this->genreateSignature($secret, $this->getPayload()) !== $this->getSignature()){
            throw new InvalidArgumentException('Signature error');
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

    protected function getSignature()
    {
        $signatureKey = $this->getHeaderSignature();
        if($this->request->headers->has($signatureKey) === false){
            throw new InvalidArgumentException('Signature must a string');
        }
        $signature = $this->request->headers->get($signatureKey);
        if(empty($signature)){
            throw new InvalidArgumentException('Signature must a string');
        }
        return $signature;
    }

    /**
     * @return string
     */
    public function getHeaderSignature(): string
    {
        return $this->headerSignature;
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

        foreach ($data as $row) {
            $result[] = $this->createCommit($row);
        }

        return $result;
    }

    abstract protected function createCommit(array $data);
}