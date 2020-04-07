<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UrlStatRepository")
 */
class UrlStat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Url", inversedBy="urlStat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $accessedAt;

    public function __construct()
    {
        $this->accessedAt = time();
    }

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Url|null
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @param Url|null $url
     * @return $this
     */
    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccessedAt(): int
    {
        return $this->accessedAt;
    }

    /**
     * @param int $accessedAt
     * @return $this
     */
    public function setAccessedAt(int $accessedAt): self
    {
        $this->accessedAt = $accessedAt;

        return $this;
    }
}
