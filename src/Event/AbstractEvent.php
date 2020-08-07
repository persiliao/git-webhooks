<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Event;

use PersiLiao\Entity\Repository;
use PersiLiao\Entity\User;

abstract class AbstractEvent
{
    /**
     * @var string
     */
    private $provider;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return AbstractEvent
     */
    public function setProvider(string $provider): AbstractEvent
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return AbstractEvent
     */
    public function setUser(User $user): AbstractEvent
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     * @return AbstractEvent
     */
    public function setRepository(Repository $repository): AbstractEvent
    {
        $this->repository = $repository;
        return $this;
    }
}