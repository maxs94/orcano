<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Twig;

use App\Service\Image\RandomImageService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RandomImageExtension extends AbstractExtension
{
    public function __construct(
        private readonly RandomImageService $randomImageService
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('random_background_image', $this->randomImageService->getRandomBackgroundImageAsUrl(...)),
        ];
    }
}
