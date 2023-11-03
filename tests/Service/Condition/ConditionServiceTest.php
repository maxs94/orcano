<?PHP 
declare(strict_types=1);

namespace App\Tests\Service\Condition;

use App\Repository\ServiceCheckConditionRepository;
use App\Service\Condition\ConditionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
* @covers \App\Service\Condition\ConditionService
*/
class ConditionServiceTest extends TestCase
{
    private ConditionService $service;

    /** @var MockObject&ServiceCheckConditionRepository */
    private ServiceCheckConditionRepository $repository;

    public function setUp(): void {
        $this->repository = $this->getMockBuilder(ServiceCheckConditionRepository::class)
            ->disableOriginalConstructor()
        ->getMock();

        $this->service = new ConditionService($this->repository);   
    }

    public function testInheritance(): void
    {
        $this->repository->method('getConditionsForServiceCheck')
            ->willReturn([]);
    }


}
