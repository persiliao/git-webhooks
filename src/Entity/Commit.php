<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\Entity;

use DateTime;

class Commit
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string[]
     */
    private $modifieds = [];

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
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Commit
     */
    public function setAuthor(string $author): Commit
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
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Commit
     */
    public function setDate(DateTime $date): Commit
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
}