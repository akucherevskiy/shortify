<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 */
class Url
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $full;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $short;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UrlStat", mappedBy="url")
     */
    private $urlStat;

    public function __construct()
    {
        $this->createdAt = time();
        $this->urlStat = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFull(): string
    {
        return $this->full;
    }

    /**
     * @param string $full
     * @return $this
     */
    public function setFull(string $full): self
    {
        $this->full = $full;

        return $this;
    }

    /**
     * @return string
     */
    public function getShort(): string
    {
        return $this->short;
    }

    /**
     * @param string $short
     * @return $this
     */
    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Collection|UrlStat[]
     */
    public function getUrlStat(): Collection
    {
        return $this->urlStat;
    }

    public function addUrlStat(UrlStat $urlStat): self
    {
        if (!$this->urlStat->contains($urlStat)) {
            $this->urlStat[] = $urlStat;
            $urlStat->setUrl($this);
        }

        return $this;
    }
}
