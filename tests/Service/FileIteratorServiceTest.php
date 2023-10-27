<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service;

use App\Service\FileIteratorService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Service\FileIteratorService
 */
class FileIteratorServiceTest extends TestCase
{
    final public const FAKE_PATH = '/../Fakes/FakeFiles/';

    public function testFileIteratorService(): void
    {
        $fileIteratorService = new FileIteratorService();
        $generator = $fileIteratorService->findFilesWithExtension(__DIR__ . self::FAKE_PATH, '.txt');

        $this->assertInstanceOf(\Generator::class, $generator);

        $files = [];
        foreach ($generator as $file) {
            $files[] = $file;
        }

        $this->assertCount(2, $files);
    }
}
