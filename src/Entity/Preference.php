<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preference
 *
 * @ORM\Table(name="preference", indexes={@ORM\Index(name="fk_categorie_preference", columns={"categorie_id"}), @ORM\Index(name="fk_membre_preference", columns={"member_id"})})
 * @ORM\Entity
 */
class Preference
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
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="idcat")
     * })
     */
    private $categorie;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

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
