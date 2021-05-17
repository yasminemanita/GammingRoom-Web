<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_membre", columns={"membreid"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{

    public function __construct()
    {
        $this->datecommande = new \DateTime(); 
    }

    /**
     * @var int
     *
     * @ORM\Column(name="idcommande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecommande", type="datetime", nullable=false)
     */
    private $datecommande ;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=0, nullable=false, options={"default"="'EnCours'"})
     */
    private $etat = '\'EnCours\'';

    /**
     * @var float
     *
     * @ORM\Column(name="totale", type="float", precision=10, scale=0, nullable=false)
     */
    private $totale = '0';

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="membreid", referencedColumnName="id")
     * })
     */
    private $membreid;

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): self
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTotale(): ?float
    {
        return $this->totale;
    }

    public function setTotale(float $totale): self
    {
        $this->totale = $totale;

        return $this;
    }

    public function getMembreid(): ?Membre
    {
        return $this->membreid;
    }

    public function setMembreid(?Membre $membreid): self
    {
        $this->membreid = $membreid;

        return $this;
    }


}
