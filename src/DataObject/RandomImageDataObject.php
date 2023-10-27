<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject;

class RandomImageDataObject implements DataObjectInterface
{
    public function __construct(
        public readonly string $imageUrl,
        public readonly string $imageCredits
    ) {
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getImageCredits(): string
    {
        return $this->imageCredits;
    }
}
