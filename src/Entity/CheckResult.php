<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\CheckResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CheckResultRepository::class)]
class CheckResult implements DataObjectInterface, ApiEntityInterface
{
    use Trait\IdTrait;
    use Trait\SetDataTrait;

    #[ORM\Column(length: 32, nullable: false)]
    private string $result;

    #[ORM\Column(type: Types::TEXT)]
    private string $message;

    #[ORM\Column(type: Types::TEXT)]
    private string $scriptOutput;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asset $asset = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceCheck $serviceCheck = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AssetServiceCheck $assetServiceCheck = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[Ignore]
    public function setData(array $data): self
    { 
        $this->setDataIfNotEmptyString($data, 'result', 'result');
        $this->setDataIfNotEmptyString($data, 'message', 'message');
        $this->setDataIfNotEmptyString($data, 'scriptOutput', 'scriptOutput');

        return $this;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setScriptOutput(string $scriptOutput): self
    {
        $this->scriptOutput = $scriptOutput;

        return $this;
    }

    public function getScriptOutput(): string
    {
        return $this->scriptOutput;
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

    public function setServiceCheck(?ServiceCheck $serviceCheck): self
    {
        $this->serviceCheck = $serviceCheck;

        return $this;
    }

    public function getAssetServiceCheck(): ?AssetServiceCheck
    {
        return $this->assetServiceCheck;
    }

    public function setAssetServiceCheck(?AssetServiceCheck $assetServiceCheck): self
    {
        $this->assetServiceCheck = $assetServiceCheck;

        return $this;
    }

}
