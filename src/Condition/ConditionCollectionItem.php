<?PHP 
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

use App\DataObject\DataObjectInterface;

class ConditionCollectionItem implements DataObjectInterface
{
    public function __construct(
        private readonly string $name,
        private AbstractCondition $condition
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getCondition(): AbstractCondition {
        return $this->condition;
    }
}
