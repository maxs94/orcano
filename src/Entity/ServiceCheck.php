<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\ServiceCheckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ServiceCheckRepository::class)]
class ServiceCheck implements DataObjectInterface, ApiEntityInterface
{
    use Trait\IdTrait;
    use Trait\SetDataTrait;

    public const DEFAULT_CHECK_INTERVAL = 60;

    public const DEFAULT_MAX_RETRIES = 3;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Ignore]
    private ?CheckScript $checkScript = null;

    #[ORM\Column]
    private int $checkIntervalSeconds = self::DEFAULT_CHECK_INTERVAL;

    #[ORM\Column]
    private int $retryIntervalSeconds = self::DEFAULT_CHECK_INTERVAL;

    #[ORM\Column]
    private int $maxRetries = self::DEFAULT_MAX_RETRIES;

    #[ORM\Column]
    private bool $notificationsEnabled = true;

    #[ORM\Column]
    private bool $enabled = true;

    #[ORM\ManyToMany(targetEntity: AssetGroup::class, inversedBy: 'serviceChecks')]
    #[Ignore]
    private Collection $assetGroups;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->assetGroups = new ArrayCollection();
    }

    #[Ignore]
    public function setData(array $data): self
    {
        $this->setDataIfNotEmptyString($data, 'name', 'name');
        $this->setDataIfNotEmptyInteger($data, 'checkIntervalSeconds', 'checkIntervalSeconds');
        $this->setDataIfNotEmptyInteger($data, 'retryIntervalSeconds', 'retryIntervalSeconds');
        $this->setDataIfNotEmptyInteger($data, 'maxRetries', 'maxRetries');

        $this->notificationsEnabled = false;
        $this->setDataIfNotEmptyBoolean($data, 'notificationsEnabled', 'notificationsEnabled');

        $this->enabled = false;
        $this->setDataIfNotEmptyBoolean($data, 'enabled', 'enabled');

        if (isset($data['checkScript']) && $data['checkScript'] instanceof CheckScript) {
            $this->setCheckScript($data['checkScript']);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCheckScript(): ?CheckScript
    {
        return $this->checkScript;
    }

    public function setCheckScript(CheckScript $checkScript): self
    {
        $this->checkScript = $checkScript;

        return $this;
    }

    public function getCheckIntervalSeconds(): int
    {
        return $this->checkIntervalSeconds;
    }

    public function setCheckIntervalSeconds(int $checkIntervalSeconds): self
    {
        $this->checkIntervalSeconds = $checkIntervalSeconds;

        return $this;
    }

    public function getRetryIntervalSeconds(): int
    {
        return $this->retryIntervalSeconds;
    }

    public function setRetryIntervalSeconds(int $retryIntervalSeconds): self
    {
        $this->retryIntervalSeconds = $retryIntervalSeconds;

        return $this;
    }

    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    public function setMaxRetries(int $maxRetries): self
    {
        $this->maxRetries = $maxRetries;

        return $this;
    }

    public function isNotificationsEnabled(): bool
    {
        return $this->notificationsEnabled;
    }

    public function setNotificationsEnabled(bool $notificationsEnabled): self
    {
        $this->notificationsEnabled = $notificationsEnabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function addAssetGroup(AssetGroup $assetGroup): self
    {
        if (!$this->assetGroups->contains($assetGroup)) {
            $this->assetGroups->add($assetGroup);
        }

        return $this;
    }

    public function removeAssetGroup(AssetGroup $assetGroup): self
    {
        $this->assetGroups->removeElement($assetGroup);

        return $this;
    }

    public function getAssetGroupsAsString(): string
    {
        $assetGroups = $this->getAssetGroups();
        $assetGroupNames = [];

        foreach ($assetGroups as $assetGroup) {
            $assetGroupNames[] = $assetGroup->getName();
        }

        return implode(', ', $assetGroupNames);
    }

    /**
     * @return Collection<int, AssetGroup>
     */
    private function getAssetGroups(): Collection
    {
        return $this->assetGroups;
    }
}
