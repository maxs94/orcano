<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\Repository\AssetGroupServiceCheckConditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetGroupServiceCheckConditionRepository::class)]
class AssetGroupServiceCheckCondition
{
    use Trait\IdTrait;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssetGroup $assetGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;

    public function getAssetGroup(): ?AssetGroup
    {
        return $this->assetGroup;
    }

    public function setAssetGroup(?AssetGroup $assetGroup): static
    {
        $this->assetGroup = $assetGroup;

        return $this;
    }

    public function getServiceCheck(): ?ServiceCheck
    {
        return $this->serviceCheck;
    }

    public function setServiceCheck(?ServiceCheck $serviceCheck): static
    {
        $this->serviceCheck = $serviceCheck;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }
}
