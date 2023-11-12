<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

use App\DataObject\DataObjectInterface;
use App\Repository\CheckScriptRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CheckScriptRepository::class)]
class CheckScript implements DataObjectInterface, ApiEntityInterface
{
    use Trait\IdTrait;
    use Trait\SetDataTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 32)]
    private ?string $filehash = null;

    private bool $isChangedInFilesystem = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[Ignore]
    public function setData(array $data): self
    {
        $this->setDataIfNotEmptyString($data, 'name', 'name');
        $this->setDataIfNotEmptyString($data, 'filename', 'filename');
        $this->setDataIfNotEmptyString($data, 'description', 'description');

        return $this;
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFilehash(): ?string
    {
        return $this->filehash;
    }

    public function setFilehash(string $filehash): self
    {
        $this->filehash = $filehash;

        return $this;
    }

    public function getIsRegistered(): bool
    {
        return $this->id !== null;
    }

    public function getIsChangedInFilesystem(): bool
    {
        return $this->isChangedInFilesystem;
    }

    public function setIsChangedInFilesystem(bool $isChangedInFilesystem): self
    {
        $this->isChangedInFilesystem = $isChangedInFilesystem;

        return $this;
    }
}
