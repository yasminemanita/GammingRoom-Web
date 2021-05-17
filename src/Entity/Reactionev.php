<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reactionev
 *
 * @ORM\Table(name="reactionev", indexes={@ORM\Index(name="fk_member_ev_reaction", columns={"membre_id"}), @ORM\Index(name="fk_evenement_reaction", columns={"evenement_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ReactionevRepository")
 */
class Reactionev
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
     * @ORM\Column(name="interaction", type="integer", nullable=false)
     */
    private $interaction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $commentaire;

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
     *   @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     * })
     */
    private $membre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInteraction(): ?int
    {
        return $this->interaction;
    }

    public function setInteraction(int $interaction): self
    {
        $this->interaction = $interaction;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }


}
