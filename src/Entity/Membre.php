<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Bafford\PasswordStrengthBundle\Validator\Constraints as BAssert;




/**
 * Membre
 *
 * @ORM\Table(name="membre", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MembreRepository")
 * @UniqueEntity("email",
 *    message="Cet email est déja utilisé" )
 */
class Membre implements UserInterface
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
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * *  @Assert\Regex(
     *     pattern = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="'{{ value }}' doit etre chaine de caractère"
     * )
     * @Assert\Length(min=3,
     *       max = 20,
     *      minMessage = "Cette chaine est trop courte.Elle doit avoir au minimum  {{ limit }} caractères",
     *      maxMessage = "Cette chaine est trop longue.Ele ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     *   @Assert\Regex(
     *     pattern = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="'{{ value }}' doit etre chaine de caractère"
     * )
     * @Assert\Length(min=3,
     *       max = 20,
     *      minMessage = "Cette chaine est trop courte.Elle doit avoir au minimum  {{ limit }} caractères",
     *      maxMessage = "Cette chaine est trop longue.Ele ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Date
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=0, nullable=false)
     *  @Assert\Choice(
     *     choices = {"Homme", "Femme"},
     *     message = "Choose a valid genre."
     * )
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_tel", type="string", length=8, nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Length(8)
     * @Assert\Regex(
     *     pattern = "/^[0-9]+$/",
     *     message="'{{ value }}' doit etre chaine des nombres"
     * )
     */
    private $numeroTel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Email(message = "Veuillez saisir une adresse email valid .'{{ value }}' n'est pas valide ")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @BAssert\PasswordStrength(
     *       minLength=6,
     *       requireNumbers=true,
     *       requireLetters = true,
     *       tooShortMessage = "Le mot de passe est trop court.Il doit avoir au minimum {{length}} caractères",
     *       missingLettersMessage = "Votre mot de passe doit contenir au minimum 1 caractère.",
     *       missingNumbersMessage = "Votre mot de passe doit contenir au minimum 1 numéro."
     *     )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=0, nullable=false)
     *   @Assert\Choice(
     *     choices = {"Coach", "Membre"},
     *     message = "Choose a valid role."
     * )
     */
    private $role;

    /**
     * @var int
     *
     * @ORM\Column(name="point", type="integer", nullable=false)
     */
    private $point = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"default"="NULL"})
     *  @Assert\Length(min=0,
     *       max = 255,
     *      minMessage = "La description est courte.Elle doit avoir au minimum {{ limit }} caractères",
     *      maxMessage = "La description est trop longue.Ele ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ban_duration", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $banDuration = NULL;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_timeban", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $lastTimeban = 'NULL';

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
    public function getMembre(): ?string
    {
        return $this->nom;
    }

    public function setMembre(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(string $numeroTel): self
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getBanDuration(): ?int
    {
        return $this->banDuration;
    }

    public function setBanDuration(?int $banDuration): self
    {
        $this->banDuration = $banDuration;

        return $this;
    }


    public function getLastTimeban(): ?\DateTimeInterface
    {
        return $this->lastTimeban=="NULL"  ? null : $this->lastTimeban;
    }

    public function setLastTimeban(?\DateTimeInterface $lastTimeban): self
    {
        $this->lastTimeban = $lastTimeban;

        return $this;
    }


    public function getRoles()
    {
        $roles =[ $this->role];
        if($this->role=="Admin")
            $roles[] = 'ROLE_ADMIN';
        else if($this->role=="Coach")
            $roles[] = 'ROLE_COACH';
        return array_unique($roles);
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function serialize() {
        return serialize($this->id);
    }

    public function unserialize($data) {
        $this->id = unserialize($data);
    }
}