<?PHP 
declare(strict_types=1);

namespace App\Service\Condition;

use App\Condition\ConditionCollection;
use App\Repository\ServiceCheckConditionRepository;

class ConditionService 
{
    public function __construct(
        private readonly ServiceCheckConditionRepository $repo
    ) { }

    public function getConditionsForServiceCheckAndAsset(
        int $serviceCheckId, 
        int $assetId): ConditionCollection 
    {
        $serviceCheckConditions = $this->repo->getConditionsForServiceCheck($serviceCheckId);

        dd($serviceCheckConditions);

    }
}
