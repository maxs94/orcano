<?PHP 
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\DataTransformer;

use PHPUnit\Framework\TestCase;

class StringDataTransformerTest extends TestCase
{
    /** @dataProvider transformStringProvider */
    public function testTransformStringToLatin(string $input, string $expected): void
    {
        $this->assertEquals($expected, StringDataTransformer::transformStringToLatin($input));
    }

    /** @return array<int, array<int, string>> */
    public function transformStringProvider(): array
    {
        return [
            ['Café au lait 123!', 'Cafe_au_lait_123'],
            ['Hello World!', 'Hello_World'],
            ['Testing 123', 'Testing_123'],
            ['Simple   test', 'Simple_test'],
            ['Numbers and special #123', 'Numbers_and_special_123'],
            ['!@#$%^&*()', ''],
            ['Underscore    between', 'Underscore_between'],
            ['Múltìplê dîäcrîtîcs', 'Multiple_diacritics'],
        ];
    }
    
}
