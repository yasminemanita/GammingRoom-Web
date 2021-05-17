<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @UniqueEntity("nomcategorie",
 *    message="Cette categorie existe déjà" )
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @Groups({"produit:read"})
     */
    private $idcat;

    /**
     * @var string
     *
     * @ORM\Column(name="nomcategorie", type="string", length=30, nullable=false)
     *  @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Regex(
     *     pattern = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="'{{ value }}' doit etre chaine de caractère"
     * )
     *  @Groups({"produit:read"})
     */
    private $nomcategorie;

    public function getIdcat(): ?int
    {
        return $this->idcat;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }
    public function getCategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setCategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }


}
