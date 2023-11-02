<?PHP 
declare(strict_types=1);

namespace App\DataObject\Collection;

class SearchResultDataObjectCollection extends DataObjectCollection
{
    private int $totalCount = 0;

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
