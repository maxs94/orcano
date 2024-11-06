<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\Condition\ConditionCollection;
use App\DataObject\DataObjectInterface;
use App\Repository\AssetServiceCheckConditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetServiceCheckConditionRepository::class)]
class AssetServiceCheckCondition implements DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asset $asset = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;

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

    public function getServiceCheck(): ?ServiceCheck
    {
        return $this->serviceCheck;
    }

    public function getServiceCheckId(): ?int
    {
        return $this->serviceCheck->getId();
    }

    public function setServiceCheck(?ServiceCheck $serviceCheck): self
    {
        $this->serviceCheck = $serviceCheck;

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
}
