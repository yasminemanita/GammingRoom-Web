<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="fk_idcat", columns={"categorie_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idevent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var string
     *
     * @ORM\Column(name="nomevent", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Titre requis")
     */
    private $nomevent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedeb", type="date", nullable=false)
     * @Assert\NotBlank(message="veuillez saisir une date")
     * @Assert\GreaterThan("today")
     */
    private $datedeb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="date", nullable=false)
     * @Assert\NotBlank(message="veuillez saisir une date")
     * @Assert\GreaterThan("today")
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Titre requis")
     *
     * @Assert\Image(
     *     allowLandscape = true,
     *     allowPortrait = true
     * )
     *
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="nbremax_participant", type="integer", nullable=false)
     * @Assert\NotBlank(message="Nombre de participant requis")
     * @Assert\DivisibleBy(2)
     */
    private $nbremaxParticipant;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description requise")
     *
    )
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true, options={"default"="NULL"})
     * @Assert\NotBlank(message="lieu requis")
     */
    private $lieu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lienyoutube", type="string", length=255, nullable=true, options={"default"="NULL"})
     * @Assert\NotBlank(message="URl requis")
     *  @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     */
    private $lienyoutube ;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="idcat")
     * })
     */
    private $categorie;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(\DateTimeInterface $datedeb): self
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNbremaxParticipant(): ?int
    {
        return $this->nbremaxParticipant;
    }

    public function setNbremaxParticipant(int $nbremaxParticipant): self
    {
        $this->nbremaxParticipant = $nbremaxParticipant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getLienyoutube(): ?string
    {
        return $this->lienyoutube;
    }

    public function setLienyoutube(?string $lienyoutube): self
    {
        $this->lienyoutube = $lienyoutube;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
