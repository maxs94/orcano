<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Scripts;

use App\Entity\CheckScript;
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
 * @covers \App\Service\Scripts\ScriptsService
 */
class ScriptsServiceTest extends TestCase
{
    final public const FAKE_PATH = '/../../Fakes/FakeScripts';

    public function testGetAllScriptsFromFilesystem(): void
    {
        /** @var MockObject&ParameterBagInterface */
        $parameterBag = $this->createMock(ParameterBagInterface::class);

        /** @var MockObject&MetaDataService $metaDataService */
        $metaDataService = $this->createMock(MetaDataService::class);

        /** @var MockObject&HashService $hashService */
        $hashService = $this->createMock(HashService::class);

        /** @var MockObject&EntityManagerInterface $em */
        $em = $this->createMock(EntityManagerInterface::class);

        /** @var MockObject&LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);

        $parameterBag->method('get')
            ->with('kernel.project_dir')
            ->willReturn(__DIR__)
        ;

        $service = new ScriptsService(
            self::FAKE_PATH,
            $parameterBag,
            $metaDataService,
            $hashService,
            $em,
            $logger
        );

        $scripts = $service->getAllScriptsFromFilesystem();

        $this->assertCount(1, $scripts);

        /** @var CheckScript $script1 */
        $script1 = $scripts->getFirst();
        $this->assertSame('script1.sh', basename($script1->getFilename()));
    }
}
