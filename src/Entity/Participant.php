<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant", indexes={@ORM\Index(name="fk_member_particper", columns={"member_id"}), @ORM\Index(name="fk_event_participant", columns={"evenement_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
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
     * @var string|null
     *
     * @ORM\Column(name="duel", type="string", length=10, nullable=true, options={"default"="NULL"})
     */
    private $duel = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="round", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $round = NULL;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement_id", referencedColumnName="idevent")
     * })
     */
    private $evenement;

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     * })
     */
    private $member;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuel(): ?string
    {
        return $this->duel;
    }

    public function setDuel(?string $duel): self
    {
        $this->duel = $duel;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(?int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getMember(): ?Membre
    {
        return $this->member;
    }

    public function setMember(?Membre $member): self
    {
        $this->member = $member;

        return $this;
    }


}
