<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /** @var array<string> */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 16)]
    private ?string $theme = 'dark';

    #[ORM\Column]
    private ?int $rowLimit = 25;

    #[ORM\Column(length: 5)]
    private ?string $language = 'auto';

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /** @param array<string, mixed> $data */
    public function setData(array $data): self
    {
        if (isset($data['email']) && !empty($data['email'])) {
            $this->setEmail($data['email']);
        }

        if (isset($data['name']) && !empty($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['password']) && ($data['password'] !== '')) {
            // todo: get setting from config
            $factory = new PasswordHasherFactory(['common' => ['algorithm' => 'bcrypt']]);

            $passwordHasher = $factory->getPasswordHasher('common');
            $hash = $passwordHasher->hash($data['password']);

            $this->setPassword($hash);
        }

        if (isset($data['theme']) && !empty($data['theme'])) {
            $this->setTheme($data['theme']);
        }

        if (isset($data['rowLimit']) && !empty($data['rowLimit'])) {
            $this->setRowLimit((int) $data['rowLimit']);
        }

        if (isset($data['language']) && !empty($data['language'])) {
            $this->setLanguage($data['language']);
        }

        $this->updatedAt = new \DateTime();

        // todo: set roles

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getRowLimit(): ?int
    {
        return $this->rowLimit;
    }

    public function setRowLimit(int $rowLimit): static
    {
        $this->rowLimit = $rowLimit;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }
}
