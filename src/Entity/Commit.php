<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\Entity;

use Carbon\Carbon;
use DateTime;

class Commit
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var User
     */
    private $author;

    /**
     * @var string
     */
    private $message;

    /**
     * @var Carbon
     */
    private $date;

    /**
     * @var string[]
     */
    private $modifieds = [];

    /**
     * @var string[]
     */
    private $removeds = [];

    /**
     * @var string[]
     */
    private $addeds = [];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Commit
     */
    public function setId(string $id): Commit
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Commit
     */
    public function setAuthor(User $author): Commit
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Commit
     */
    public function setMessage(string $message): Commit
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @param Carbon $date
     * @return Commit
     */
    public function setDate(Carbon $date): Commit
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getModifieds(): array
    {
        return $this->modifieds;
    }

    /**
     * @param string[] $modifieds
     * @return Commit
     */
    public function setModifieds(array $modifieds): Commit
    {
        $this->modifieds = $modifieds;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRemoveds(): array
    {
        return $this->removeds;
    }

    /**
     * @param string[] $removeds
     * @return Commit
     */
    public function setRemoveds(array $removeds): Commit
    {
        $this->removeds = $removeds;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAddeds(): array
    {
        return $this->addeds;
    }

    /**
     * @param string[] $addeds
     * @return Commit
     */
    public function setAddeds(array $addeds): Commit
    {
        $this->addeds = $addeds;
        return $this;
    }
}