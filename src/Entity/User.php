<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="I think you're already registered!"
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="I think you're already registered!"
 * )
 */
class User implements UserInterface
{
    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"create","default"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least 2 characters",
     *      maxMessage = "Your name cannot be longer than 50 characters"
     * )
     * @Assert\NotBlank(message="name required")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"create","default"})
     * @Assert\NotBlank(message="username required")
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @Groups({"create","default"})
     * @Assert\NotBlank(message="email required")
     * @Assert\Email(
     *     message = "The email is not a valid email."
     * )
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Groups({"create"})
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your name must be at least 8 characters",
     *      maxMessage = "Your first name cannot be longer than 50 characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->isAdmin = false;
    }

   /**
    * @return string
    */
    public function getUsername()
    {
        return $this->username;
    }

   /**
    * @param mixed $username
    */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getRoles(): array
    {
        return array('ROLE_USER');
    }

    public function getSalt(): void
    {
        return;
    }

    public function eraseCredentials()
    {
        
    }
}
