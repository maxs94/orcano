<?php

namespace App\DataObject\Page;

interface PageDataObjectInterface
{
    public function getTitle(): ?string;
    public function setTitle(?string $title): self;
}
