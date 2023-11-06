<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\AssetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
class Asset implements DataObjectInterface
{
    use Trait\IdTrait;

    #[ORM\Column(length: 255)]
    private ?string $hostname = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $ipv4Address = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipv6Address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: AssetGroup::class, inversedBy: 'assets')]
    private Collection $assetGroups;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->assetGroups = new ArrayCollection();
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): static
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getIpv4Address(): ?string
    {
        return $this->ipv4Address;
    }

    public function setIpv4Address(?string $ipv4Address): static
    {
        $this->ipv4Address = $ipv4Address;

        return $this;
    }

    public function getIpv6Address(): ?string
    {
        return $this->ipv6Address;
    }

    public function setIpv6Address(?string $ipv6Address): static
    {
        $this->ipv6Address = $ipv6Address;

        return $this;
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

    /**
     * @return Collection<int, AssetGroup>
     */
    public function getAssetGroups(): Collection
    {
        return $this->assetGroups;
    }

    public function addAssetGroup(AssetGroup $assetGroup): static
    {
        if (!$this->assetGroups->contains($assetGroup)) {
            $this->assetGroups->add($assetGroup);
        }

        return $this;
    }

    public function removeAssetGroup(AssetGroup $assetGroup): static
    {
        $this->assetGroups->removeElement($assetGroup);

        return $this;
    }

    public function getAssetGroupsAsString(): string
    {
        $assetGroups = $this->getAssetGroups();
        $assetGroupsAsString = '';
        foreach ($assetGroups as $assetGroup) {
            $assetGroupsAsString .= $assetGroup->getName() . ', ';
        }

        return rtrim($assetGroupsAsString, ', ');
    }
}