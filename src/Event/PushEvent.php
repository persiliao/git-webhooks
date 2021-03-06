<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Event;

use PersiLiao\GitWebhooks\Entity\Commit;
use function array_merge;

class PushEvent extends AbstractEvent
{
    public const EVENT_NAME = 'push';
    /**
     * @var string
     */
    private $before;

    /**
     * @var string
     */
    private $after;

    /**
     * @var string
     */
    private $ref;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $branchName;

    /**
     * @var string
     */
    private $tagName;

    /**
     * @var Commit[]
     */
    private $commits = [];

    /**
     * @return string
     */
    public function getBefore(): string
    {
        return $this->before;
    }

    /**
     * @param string $before
     * @return PushEvent
     */
    public function setBefore(string $before): PushEvent
    {
        $this->before = $before;
        return $this;
    }

    /**
     * @return string
     */
    public function getAfter(): string
    {
        return $this->after;
    }

    /**
     * @param string $after
     * @return PushEvent
     */
    public function setAfter(string $after): PushEvent
    {
        $this->after = $after;
        return $this;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     * @return PushEvent
     */
    public function setRef(string $ref): PushEvent
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return PushEvent
     */
    public function setType(string $type): PushEvent
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }

    /**
     * @param string $branchName
     * @return PushEvent
     */
    public function setBranchName(string $branchName): PushEvent
    {
        $this->branchName = $branchName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     * @return PushEvent
     */
    public function setTagName(string $tagName): PushEvent
    {
        $this->tagName = $tagName;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getModifieds(): array
    {
        $modifieds = [];
        foreach($this->getCommits() as $commit){
            $modifieds = array_merge($modifieds, $commit->getModifieds());
        }
        return $modifieds;
    }

    /**
     * @return Commit[]
     */
    public function getCommits(): array
    {
        return $this->commits;
    }

    /**
     * @param Commit[] $commits
     * @return PushEvent
     */
    public function setCommits(array $commits): PushEvent
    {
        $this->commits = $commits;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAddeds(): array
    {
        $addeds = [];
        foreach($this->getCommits() as $commit){
            $addeds = array_merge($addeds, $commit->getAddeds());
        }
        return $addeds;
    }

    /**
     * @return string[]
     */
    public function getRemoves(): array
    {
        $removeds = [];
        foreach($this->getCommits() as $commit){
            $removeds = array_merge($removeds, $commit->getRemoveds());
        }
        return $removeds;
    }
}