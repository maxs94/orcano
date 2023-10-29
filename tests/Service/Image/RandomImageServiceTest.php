<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Image;

use App\Service\Image\RandomImageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @internal
 *
 * @covers \App\Service\Image\RandomImageService
 */
class RandomImageServiceTest extends TestCase
{
    public const FAKE_PATH = '/../../Fakes';

    /** @var MockObject&Packages */
    private Packages $packages;

    /** @var MockObject&ParameterBagInterface */
    private ParameterBagInterface $parameterBag;

    private RandomImageService $service;

    public function setUp(): void
    {
        $this->packages = $this->createMock(Packages::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->service = new RandomImageService($this->packages, $this->parameterBag);
    }

    public function testNoImagesFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->getRandomBackgroundImageAsUrl();
    }

    public function testGetRandomBackgroundImageAsUrl(): void
    {
        $this->parameterBag->expects($this->once())
            ->method('get')
            ->with('kernel.project_dir')
            ->willReturn(__DIR__ . self::FAKE_PATH)
        ;

        $this->packages->expects($this->once())
            ->method('getUrl')
            ->with('/images/backgrounds/test1.jpg')
            ->willReturn('http://localhost/images/backgrounds/test1.jpg')
        ;

        $randomImage = $this->service->getRandomBackgroundImageAsUrl();

        $this->assertSame('http://localhost/images/backgrounds/test1.jpg', $randomImage->getImageUrl());
        $this->assertSame('Image Credits Test', $randomImage->getImageCredits());
    }
}
