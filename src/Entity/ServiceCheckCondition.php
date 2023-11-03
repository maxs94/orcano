<?php

namespace App\Entity;

use App\Repository\ServiceCheckConditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceCheckConditionRepository::class)]
class ServiceCheckCondition
{
    use Trait\IdTrait;

    public const REFERENCE_ENTITY_ASSET = 'asset';
    public const REFERENCE_ENTITY_ASSET_GROUP = 'asset_group';
    public const REFERENCE_ENTITY_SERVICE_CHECK = 'service_check';

    public const INHERITANCE_ORDER = [
        self::REFERENCE_ENTITY_ASSET,
        self::REFERENCE_ENTITY_ASSET_GROUP,
        self::REFERENCE_ENTITY_SERVICE_CHECK,
    ];

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\Column]
    private ?int $referenceId = null;

    #[ORM\Column(length: 32)]
    private ?string $referenceEntityClass = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $conditions = null;

    public function getServiceCheck(): ?ServiceCheck
    {
        return $this->serviceCheck;
    }

    public function setServiceCheck(?ServiceCheck $serviceCheck): static
    {
        $this->serviceCheck = $serviceCheck;

        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->referenceId;
    }

    public function setReferenceId(int $referenceId): static
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getReferenceEntityClass(): ?string
    {
        return $this->referenceEntityClass;
    }

    public function setReferenceEntityClass(string $referenceEntityClass): static
    {
        $this->referenceEntityClass = $referenceEntityClass;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }
}
