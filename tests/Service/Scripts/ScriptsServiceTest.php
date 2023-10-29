<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Scripts;

use App\DataObject\Collection\DataObjectCollection;
use App\Entity\CheckScript;
use App\Repository\CheckScriptRepository;
use App\Service\Scripts\HashService;
use App\Service\Scripts\MetaDataService;
use App\Service\Scripts\ScriptsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class ScriptsServiceTest extends TestCase
{
    public const FAKE_PATH = '/../../Fakes/FakeScripts';

    private ScriptsService $service;

    /** @var MockObject&EntityManagerInterface */
    private EntityManagerInterface $em;

    public function setUp(): void
    {
        /** @var MockObject&ParameterBagInterface */
        $parameterBag = $this->createMock(ParameterBagInterface::class);

        /** @var MockObject&MetaDataService $metaDataService */
        $metaDataService = $this->createMock(MetaDataService::class);

        /** @var MockObject&HashService $hashService */
        $hashService = $this->createMock(HashService::class);

        $this->em = $this->createMock(EntityManagerInterface::class);

        /** @var MockObject&LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        $parameterBag->method('get')
            ->with('kernel.project_dir')
            ->willReturn(__DIR__)
        ;

        $this->service = new ScriptsService(
            self::FAKE_PATH,
            $parameterBag,
            $metaDataService,
            $hashService,
            $this->em,
            $logger
        );
    }

    public function testRefreshScripts(): void
    {
        /** @var MockObject&CheckScriptRepository */
        $checkScriptRepository = $this->createMock(CheckScriptRepository::class);
        $this->em->method('getRepository')->willReturn($checkScriptRepository);

        $checkScriptRepository->method('findOneBy')->willReturn(null);

        $result = $this->service->refreshScripts();

        $this->assertTrue($result);
    }

    public function testGetAllScripts(): void
    {
        /** @var MockObject&CheckScriptRepository */
        $checkScriptRepository = $this->createMock(CheckScriptRepository::class);
        $this->em->method('getRepository')->willReturn($checkScriptRepository);

        $checkScriptRepository->method('findAllAsCollection')->willReturn(new DataObjectCollection());

        $result = $this->service->getAllScripts();

        $this->assertInstanceOf(DataObjectCollection::class, $result);
        $this->assertCount(1, $result);

        /** @var CheckScript $script */
        $script = $result->getFirst();
        $this->assertInstanceOf(CheckScript::class, $script);

        $this->assertSame('script1.sh', basename($script->getFilename()));
        $this->assertTrue($script->getIsChangedInFilesystem());
    }

    public function testGetAllScriptsFromFilesystem(): void
    {
        $scripts = $this->service->getAllScriptsFromFilesystem();

        $this->assertCount(1, $scripts);

        /** @var CheckScript $script1 */
        $script1 = $scripts->getFirst();
        $this->assertSame('script1.sh', basename($script1->getFilename()));
    }
}
