<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Entity;

class Repository
{
    public const BRANCH_MASTER = 'master';
    public const TYPE_BRANCH = 'branch';
    public const TYPE_TAG = 'tag';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var string
     */
    private $url;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Repository
     */
    public function setId(int $id): Repository
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Repository
     */
    public function setName(string $name): Repository
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return Repository
     */
    public function setFullName(string $fullName): Repository
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return Repository
     */
    public function setNamespace(string $namespace): Repository
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Repository
     */
    public function setDescription(string $description): Repository
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage(): string
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     * @return Repository
     */
    public function setHomepage(string $homepage): Repository
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Repository
     */
    public function setUrl(string $url): Repository
    {
        $this->url = $url;
        return $this;
    }
}