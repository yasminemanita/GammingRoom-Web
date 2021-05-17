<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Participantcours
 *
 * @ORM\Table(name="participantcours", indexes={@ORM\Index(name="fk_cours_participant", columns={"cour_id"}), @ORM\Index(name="fk_membre_participant", columns={"membre_id"})})
 * @ORM\Entity
 * * @ORM\Entity(repositoryClass="App\Repository\ParticipantcoursRepository")
 */
class Participantcours
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
     * @var \Cour
     *
     * @ORM\ManyToOne(targetEntity="Cour")
     * @Assert\NotBlank()
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cour_id", referencedColumnName="id")
     * })
     */
    private $cour;

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @Assert\NotBlank()
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     * })
     */
    private $membre;

    public function getId(): ?int
    {
        return $this->id;
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
