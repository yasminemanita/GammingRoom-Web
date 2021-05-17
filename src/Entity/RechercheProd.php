<?php

namespace App\Entity;

use App\Repository\RechercheProdRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RechercheProdRepository::class)
 */
class RechercheProd
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**

     * @ORM\Column(type="string")
     */
    private $desc;

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc): void
    {
        $this->desc = $desc;
    }



}
