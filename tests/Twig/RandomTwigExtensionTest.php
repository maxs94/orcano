<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Twig;

use App\Service\Image\RandomImageService;
use App\Twig\RandomImageExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Twig\RandomImageExtension
 *
 * @internal
 */
class RandomImageExtensionTest extends TestCase
{
    public function testFunction(): void
    {
        $randomImageService = $this->createMock(RandomImageService::class);
        $extension = new RandomImageExtension($randomImageService);

        $functions = $extension->getFunctions();
        $this->assertCount(1, $functions);

        $function = $functions[0];
        $this->assertInstanceOf(\Twig\TwigFunction::class, $function);

        $callable = $function->getCallable();
        $this->assertIsCallable($callable);
    }
}
