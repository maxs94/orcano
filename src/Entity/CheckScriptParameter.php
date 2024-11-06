<?php

namespace App\Entity;

use App\Repository\CheckScriptParameterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CheckScriptParameterRepository::class)]
class CheckScriptParameter
{
    use Trait\IdTrait;

    #[ORM\ManyToOne(inversedBy: 'checkScriptParameters', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?CheckScript $checkScript = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\Column(length: 32)]
    private ?string $dataType;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckScript(): ?CheckScript
    {
        return $this->checkScript;
    }

    public function setCheckScript(?CheckScript $checkScript): static
    {
        $this->checkScript = $checkScript;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function setDataType(string $dataType): static
    {
        $this->dataType = $dataType;

        return $this;
    }
}
