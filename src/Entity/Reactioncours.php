<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reactioncours
 *
 * @ORM\Table(name="Reactioncours", indexes={@ORM\Index(name="fk_member_reaction", columns={"membre_id"}), @ORM\Index(name="fk_cour_reaction", columns={"cour_id"})})
 * @ORM\Entity
 * * @ORM\Entity(repositoryClass="App\Repository\ReactioncoursRepository")
 */
class Reactioncours
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
    private $commentaire = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $dateCreation = 'current_timestamp()';

    /**
     * @var \Cour
     *
     * @ORM\ManyToOne(targetEntity="Cour")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cour_id", referencedColumnName="id")
     * })
     */
    private $cour;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation=="current_timestamp()" ? null : $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCour(): ?Cour
    {
        return $this->cour;
    }

    public function setCour(?Cour $cour): self
    {
        $this->cour = $cour;

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
