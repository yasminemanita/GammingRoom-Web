<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Jeux
 *
 * @ORM\Table(name="jeux")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\JeuxRepository")
 */
class Jeux
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
     *
     * @ORM\Column(name="nom", type="string", length=40, nullable=false)
     * @Assert\NotBlank(message="Nom requis")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     * @Assert\NotBlank(message="Description requis")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type_plateforme", type="string", length=0, nullable=false, options={"default"="'Desktop'"})
     */
    private $typePlateforme = '\'Desktop\'';

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=false)
     */
    private $image;

    
    
    private $fileimage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getTypePlateforme(): ?string
    {
        return $this->typePlateforme;
    }

    public function setTypePlateforme(string $typePlateforme): self
    {
        $this->typePlateforme = $typePlateforme;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getFileimage(): ?string
    {
        return $this->image;
    }

    public function setFileimage(?string $image): self
    {
        $this->fileimage = $image;

        return $this;
    }



}
