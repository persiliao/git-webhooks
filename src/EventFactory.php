<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use Closure;
use PersiLiao\Event\PingEvent;
use PersiLiao\Event\PushEvent;
use PersiLiao\GitWebhooks\Event\AbstractEvent;

class EventFactory
{
    /**
     * @var ProviderInterface[]
     */
    protected $providers = [];

    /**
     * EventFactory constructor.
     * @param ProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        foreach($providers as $provider){
            $this->addProvider($provider);
        }
    }

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * @return AbstractEvent|PushEvent|PingEvent
     */
    public function create(): AbstractEvent
    {
        foreach($this->providers as $provider){
            if(!$provider->support()){
                continue;
            }

            return $provider->create();
        }
    }

    public function __call($name, $arguments)
    {
        foreach($this->providers as $provider){
            if(!$provider->support()){
                continue;
            }

            $provider->addHandle($name, $arguments[0], $arguments[1]);
        }
    }
}