<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="fk_idcatP", columns={"id_cat"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 *
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="idprod", type="integer", nullable=false)

     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"produit:read"})
     */
    private $idprod;

    /**
     * @var string

     * @Assert\NotBlank()
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     * @ORM\Column(name="image", type="string", length=150, nullable=false)
     * @Groups({"produit:read"})
     */
    private $image;

    /**
     * @var string

     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     *  @Groups({"produit:read"})
     */
    private $libelle;

    /**
     * @var float

     * @Assert\NotBlank()
     * * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )

     * @Assert\Positive(message ="the numbre should be positive")

     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     *  @Groups({"produit:read"})
     */
    private $prix;

    /**
     * @var string

     * @Assert\NotBlank()

     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     *  @Groups({"produit:read"})
     */
    private $description;

    /**


     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat", referencedColumnName="idcat")
     * })
     * @Groups({"produit:read"})

     */
    private $idCat;

    public function getIdprod(): ?int
    {
        return $this->idprod;
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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

    public function getIdCat(): ?Categorie
    {
        return $this->idCat;
    }

    public function setIdCat(?Categorie $idCat): self
    {
        $this->idCat = $idCat;

        return $this;
    }


}
