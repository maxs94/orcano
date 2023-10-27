<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\UserSettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSettingRepository::class)]
class UserSetting implements DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\ManyToOne(inversedBy: 'userSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 32)]
    private ?string $theme = null;

    #[ORM\Column]
    private ?int $tableRowLimit = null;

    #[ORM\Column(length: 5)]
    private ?string $locale = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getTableRowLimit(): ?int
    {
        return $this->tableRowLimit;
    }

    public function setTableRowLimit(int $tableRowLimit): static
    {
        $this->tableRowLimit = $tableRowLimit;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }
}
