<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\AssetGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: AssetGroupRepository::class)]
class AssetGroup implements DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[Ignore]
    #[ORM\ManyToMany(targetEntity: Asset::class, mappedBy: 'assetGroups')]
    private Collection $assets;

    #[ORM\ManyToMany(targetEntity: ServiceCheck::class, mappedBy: 'assetGroups')]
    private Collection $serviceChecks;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->serviceChecks = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAssetCount(): int
    {
        return $this->assets->count();
    }

    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): static
    {
        if (!$this->assets->contains($asset)) {
            $this->assets->add($asset);
            $asset->addAssetGroup($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): static
    {
        if ($this->assets->removeElement($asset)) {
            $asset->removeAssetGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ServiceCheck>
     */
    public function getServiceChecks(): Collection
    {
        return $this->serviceChecks;
    }

    public function addServiceCheck(ServiceCheck $serviceCheck): static
    {
        if (!$this->serviceChecks->contains($serviceCheck)) {
            $this->serviceChecks->add($serviceCheck);
            $serviceCheck->addAssetGroup($this);
        }

        return $this;
    }

    public function removeServiceCheck(ServiceCheck $serviceCheck): static
    {
        if ($this->serviceChecks->removeElement($serviceCheck)) {
            $serviceCheck->removeAssetGroup($this);
        }

        return $this;
    }
}
