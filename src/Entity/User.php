<?php

namespace App\Entity;

use App\Entity\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otp = null;

    /**
     * @var RoleEnum[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return [RoleEnum::USER->value, ...array_map(fn ($role) => $role->value, $this->roles)];
    }

    /**
     * @param RoleEnum[] $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(RoleEnum $role): static
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(RoleEnum $role): static
    {
        $key = array_search($role, $this->roles, true);

        if (false !== $key) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    public function getOtp(): ?string
    {
        return $this->otp;
    }

    public function setOtp(?string $otp): static
    {
        $this->otp = $otp;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        if (null === $this->username) {
            throw new \LogicException('The UserIdentifier is empty');
        }

        return $this->username;
    }

    public function eraseCredentials(): void
    {
    }
}
