<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;


/**
 * Cour
 *
 * @ORM\Table(name="cour", indexes={@ORM\Index(name="fk_member_cour", columns={"membre_id"}), @ORM\Index(name="fk_categorie_cour", columns={"categorie_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CourRepository")
 */
class Cour
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nomCours", type="string", length=100, nullable=false)

     */
    private $nomcours;

    /**
     * @var string
     * @Assert\NotBlank(message="description required")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive(message="the number should be positive")
     * @ORM\Column(name="nb_participant", type="integer", nullable=false)
     */
    private $nbParticipant;

    /**
     * @var \DateTime
     *  @Assert\NotBlank()
     * @ORM\Column(name="date_creation", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $dateCreation = 'current_timestamp()';

    /**
     * @var string

     * @ORM\Column(name="tags", type="string", length=255, nullable=false)
     */
    private $tags;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     * @ORM\Column(name="imagecours", type="string", length=250, nullable=false)
     */
    private $imagecours;

    /**
     * @var string
     * @Assert\NotBlank()
     *@Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     * @ORM\Column(name="lienYoutube", type="string", length=255, nullable=false)
     */
    private $lienyoutube;

    /**
     * @var \Categorie
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="idcat")
     * })
     */
    private $categorie;

    /**
     * @var \Membre
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     * })
     */
    private $membre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pourcentageLike;



    public function __construct()
    {
        $this->dateCreation = new \DateTime('now');

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcours(): ?string
    {
        return $this->nomcours;
    }

    public function setNomcours(string $nomcours): self
    {
        $this->nomcours = $nomcours;

        return $this;
    }
    public function getCours(): ?string
    {
        return $this->nomcours;
    }

    public function setCours(string $nomcours): self
    {
        $this->nomcours = $nomcours;

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

    public function getNbParticipant(): ?int
    {
        return $this->nbParticipant;
    }

    public function setNbParticipant(int $nbParticipant): self
    {
        $this->nbParticipant = $nbParticipant;

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

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getImagecours(): ?string
    {
        return $this->imagecours;
    }

    public function setImagecours(string $imagecours): self
    {
        $this->imagecours = $imagecours;

        return $this;
    }

    public function getLienyoutube(): ?string
    {
        return $this->lienyoutube;
    }

    public function setLienyoutube(string $lienyoutube): self
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

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getPourcentageLike(): ?float
    {
        return $this->pourcentageLike;
    }

    public function setPourcentageLike(?float $pourcentageLike): self
    {
        $this->pourcentageLike = $pourcentageLike;

        return $this;
    }


}
