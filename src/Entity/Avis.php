<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Avis
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AvisRepository")
 */
class Avis
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
     * @ORM\Column(name="avis", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Avis requis")
     * */
    private $avis;

    /**
     * @var \Membre
     *
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id ", referencedColumnName="id")
     * })
     */
    private $membre;

    
    /**
     * @var string
     *
     * @ORM\Column(name="sentiment", type="string", length=5, nullable=false, options={"default"="'NONE'"})
     */
    private $sentiment = '\'NONE\'';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(string $avis): self
    {
        $this->avis = $avis;

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

    public function getSentiment(): ?string
    {
        return $this->sentiment;
    }

    public function setSentiment(string $sentiment): self
    {
        $this->sentiment = $sentiment;

        return $this;
    }

   


}
