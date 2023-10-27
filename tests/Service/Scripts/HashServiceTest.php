<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Scripts;

use App\Service\Scripts\HashService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class HashServiceTest extends TestCase
{
    public const FAKE_PATH = '/../../Fakes/FakeScripts';

    public function testCreateHashFromFile(): void
    {
        $service = new HashService();
        $hash = $service->createHashFromFile(__DIR__ . self::FAKE_PATH . '/script1.sh');

        $this->assertSame('27a5ae87dd949061f79612cd9fa53c3e', $hash);
    }

    public function testFileDoesNotExist(): void
    {
        $this->expectException(\Exception::class);
        $service = new HashService();
        $service->createHashFromFile('doesnotexist.txt');
    }
}
