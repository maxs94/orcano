<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\ServiceCheckWorkerStatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceCheckWorkerStatsRepository::class)]
class ServiceCheckWorkerStats implements DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastCheck = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Worker $worker = null;

    #[ORM\Column]
    private ?int $retryNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asset $asset = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getLastCheck(): ?\DateTimeInterface
    {
        return $this->lastCheck;
    }

    public function setLastCheck(\DateTimeInterface $lastCheck): static
    {
        $this->lastCheck = $lastCheck;

        return $this;
    }

    public function getWorker(): ?Worker
    {
        return $this->worker;
    }

    public function setWorker(?Worker $worker): static
    {
        $this->worker = $worker;

        return $this;
    }

    public function getRetryNumber(): ?int
    {
        return $this->retryNumber;
    }

    public function setRetryNumber(int $retryNumber): static
    {
        $this->retryNumber = $retryNumber;

        return $this;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): static
    {
        $this->asset = $asset;

        return $this;
    }
}
