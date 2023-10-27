<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Scripts;

use App\Service\Scripts\MetaDataService;
use App\Service\Scripts\ScriptsService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class MetaDataServiceTest extends TestCase
{
    public const FAKE_PATH = '/../../Fakes/FakeScripts';

    private MetaDataService $metaDataService;

    public function setUp(): void
    {
        $this->metaDataService = new MetaDataService();
    }

    public function testExtractMetaDataFromFile(): void
    {
        $result = $this->metaDataService->extractMetaDataFromFile(__DIR__ . self::FAKE_PATH . '/script1.sh', ScriptsService::VALID_METADATA_KEYS);
        $this->assertSame('test script1', $result->getName());
        $this->assertSame('does this and that', $result->getDescription());
    }

    /** @dataProvider extractMetaDataFromStringDataProvider */
    public function testExtractMetaDataFromString(array $expected, string $input): void
    {
        $result = $this->metaDataService->extractMetaDataFromString($input, ScriptsService::VALID_METADATA_KEYS);
        $this->assertSame($expected, $result);
    }

    public function extractMetaDataFromStringDataProvider(): array
    {
        return [
            'empty string' => [
                'expected' => [],
                'input' => '',
            ],
            'no meta data' => [
                'expected' => [],
                'input' => 'echo "Hello World"',
            ],
            'meta data' => [
                'expected' => [
                    'name' => 'Test Script',
                ],
                'input' => '# name: Test Script',
            ],
            'meta data comment starting with whitespace' => [
                'expected' => [
                    'name' => 'Test Script',
                ],
                'input' => '    # name: Test Script',
            ],
        ];
    }
}
