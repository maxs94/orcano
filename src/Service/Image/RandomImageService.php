<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Image;

use App\DataObject\RandomImageDataObject;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RandomImageService
{
    public const BACKGROUND_IMAGES_PATH = '/images/backgrounds';

    public function __construct(
        private readonly Packages $packages,
        private readonly ParameterBagInterface $parameterBag
    ) {}

    public function getRandomBackgroundImageAsUrl(): RandomImageDataObject
    {
        $dir = $this->parameterBag->get('kernel.project_dir') . '/public/' . self::BACKGROUND_IMAGES_PATH;
        $images = glob($dir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        if (empty($images)) {
            throw new \RuntimeException('No images found in ' . $dir);
        }

        $randomImage = $images[array_rand($images)];
        $url = $this->packages->getUrl(self::BACKGROUND_IMAGES_PATH . '/' . basename($randomImage));
        $credits = $this->getImageCredits($randomImage);

        return new RandomImageDataObject($url, $credits);
    }

    private function getImageCredits(string $filename): string
    {
        $creditFile = $filename . '.txt';
        if (file_exists($creditFile)) {
            $contents = file_get_contents($creditFile);

            return rtrim($contents, PHP_EOL);
        }

        return '';
    }
}
