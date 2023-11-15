<?PHP 
declare(strict_types=1);

namespace App\Tests\Condition;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
use PHPUnit\Framework\TestCase;

class ConditionCollectionSerializeTest extends TestCase
{
    public function testSerialization(): void 
    {
        $conditionCollection = new ConditionCollection();
        $condition1 = new EqualsCondition('test', 'test');
        $condition2 = new MinMaxCondition(1, 2, 3);

        $conditionCollection->addCondition('test', $condition1);
        $conditionCollection->addCondition('test2', $condition2);

        $serialized = serialize($conditionCollection);
        $unserialized = unserialize($serialized);

        $this->assertEquals($conditionCollection, $unserialized);
    }
}
