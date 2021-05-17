<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification", indexes={@ORM\Index(name="fk_notif_membre", columns={"to"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="title", type="integer", nullable=false)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="body", type="integer", nullable=false)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $date = 'current_timestamp()';

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to", referencedColumnName="id")
     * })
     */
    private $to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?int
    {
        return $this->title;
    }

    public function setTitle(int $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?int
    {
        return $this->body;
    }

    public function setBody(int $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTo(): ?Membre
    {
        return $this->to;
    }

    public function setTo(?Membre $to): self
    {
        $this->to = $to;

        return $this;
    }


}
