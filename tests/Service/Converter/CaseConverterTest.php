<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Converter;

use App\Service\Converter\CaseConverter;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CaseConverterTest extends TestCase
{
    public function testCamelCaseToSnakeCase(): void
    {
        $this->assertSame('test_string', CaseConverter::camelCaseToSnakeCase('testString'));
    }

    public function testSnakeCaseToCamelCase(): void
    {
        $this->assertSame('testString', CaseConverter::snakeCaseToCamelCase('test_string'));
    }

    public function testKebabCaseToCamelCase(): void
    {
        $this->assertSame('testString', CaseConverter::kebabCaseToCamelCase('test-string'));
    }

    public function testCamelCaseToKebabCase(): void
    {
        $this->assertSame('test-string', CaseConverter::camelCaseToKebabCase('testString'));
    }
}
