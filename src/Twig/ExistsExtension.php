<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Twig;

use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ExistsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('existsInCollection', $this->existsInCollection(...)),
        ];
    }

    public function existsInCollection(Collection $collection, string $key, mixed $value): bool
    {
        $results = $collection->filter(fn ($item): bool => $item->{$key}() === $value);

        return $results->count() > 0;
    }
}
