<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Twig;

use Orcano\Service\Converter\TimeConverter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeConverterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('millisecondsToTime', $this->millisecondsToTime(...)),
        ];
    }

    public function millisecondsToTime(float $ms): string
    {
        return TimeConverter::millisecondsToTime($ms);
    }
}
