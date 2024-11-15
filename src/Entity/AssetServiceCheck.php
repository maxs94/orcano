<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\Condition\ConditionCollection;
use App\DataObject\DataObjectInterface;
use App\Entity\Trait\IdTrait;
use App\Repository\AssetServiceCheckRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetServiceCheckRepository::class)]
class AssetServiceCheck implements DataObjectInterface
{
    use IdTrait;

    #[ORM\ManyToOne(inversedBy: 'assetServiceChecks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asset $asset = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;

    /** @var ?array<string, mixed> */
    #[ORM\Column(nullable: true)]
    private ?array $config = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getServiceCheckId(): ?int 
    {
        return $this->serviceCheck?->getId();
    }

    public function getServiceCheck(): ?ServiceCheck
    {
        return $this->serviceCheck;
    }

    public function setServiceCheck(?ServiceCheck $serviceCheck): self
    {
        $this->serviceCheck = $serviceCheck;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }
    
    public function setConditionCollection(ConditionCollection $conditionCollection): self
    {
        $this->conditions = serialize($conditionCollection);

        return $this;
    }

    public function getConditionCollection(): ConditionCollection
    {
        return unserialize($this->conditions, [ConditionCollection::class]);
    }

    /** @return ?array<string, mixed> */
    public function getConfig(): ?array
    {
        return $this->config;
    }

    /** @param ?array<string, mixed> $config */
    public function setConfig(?array $config): self
    {
        $this->config = $config;

        return $this;
    }
}
