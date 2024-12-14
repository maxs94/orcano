<?PHP 
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service;

use App\Service\MySqlTypeService;
use PHPUnit\Framework\TestCase;

class MySqlTypeServiceTest extends TestCase
{
    /** @dataProvider getTypeDataProvider */
    public function testGetType(string $input, string $expected): void
    {
        $this->assertEquals($expected, MySqlTypeService::getType($input));
    }

    /** @return array<int, array<int, string>> */
    public function getTypeDataProvider(): array
    {
        return [
            ['1', 'INT'],
            ['1.0', 'FLOAT'],
            ['true', 'BOOLEAN'],
            ['2023-01-01', 'DATE'],
            ['2023-01-01 00:00:00', 'DATETIME'],
            ['00:00:00', 'TIME'],
            ['hello world', 'VARCHAR(255)'],
        ];
    }
    
}
