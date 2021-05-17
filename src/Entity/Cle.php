<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Cle
 *
 * @ORM\Table(name="cle", indexes={@ORM\Index(name="fk_produit_id", columns={"produit_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CleRepository")
 */
class Cle
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcle", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcle;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=50, nullable=false)
     */
    private $code;

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produit_id ", referencedColumnName="idprod")
     * })
     */
    private $produit;

    public function getIdcle(): ?int
    {
        return $this->idcle;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getProduit()
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }



}
