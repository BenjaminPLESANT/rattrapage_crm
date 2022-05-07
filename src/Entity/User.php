<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class), HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $accessToken;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $refreshToken;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $refreshTokenAt;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isTokenValid;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isTokenRevoked;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        if (!empty($this->getRoles())) {
            $this->setRoles(['ROLE_USER']);
        }

        if (empty($this->getAccessToken())) {
            $this->setAccessToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
        }

    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getRefreshTokenAt(): ?\DateTimeImmutable
    {
        return $this->refreshTokenAt;
    }

    public function setRefreshTokenAt(?\DateTimeImmutable $refreshTokenAt): self
    {
        $this->refreshTokenAt = $refreshTokenAt;

        return $this;
    }

    public function getIsTokenValid(): ?bool
    {
        return $this->isTokenValid;
    }

    public function setIsTokenValid(?bool $isTokenValid): self
    {
        $this->isTokenValid = $isTokenValid;

        return $this;
    }

    public function getIsTokenRevoked(): ?bool
    {
        return $this->isTokenRevoked;
    }

    public function setIsTokenRevoked(?bool $isTokenRevoked): self
    {
        $this->isTokenRevoked = $isTokenRevoked;

        return $this;
    }



}
