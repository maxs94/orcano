<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Message\CheckNotification;
use App\Repository\CheckScriptRepository;
use App\Service\Scripts\ScriptRunnerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckScriptTestPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly ScriptRunnerService $scriptRunnerService,
        private readonly CheckScriptRepository $checkScriptRepository
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/check-script/test/{id}', name: 'test_check_script', methods: ['POST'])]
    public function testAction(Request $request, Context $context, int $id = null): Response
    {
        $config = $this->getCheckScriptParameter($request);

        $scriptFile = $this->getScriptFile($id);

        $message = new CheckNotification(
            0,
            0,
            0,
            $this->getHostname($config),
            $this->getIpv4Address($config),
            $this->getIpv6Address($config),
            $scriptFile,
            $config
        );

        $result = $this->scriptRunnerService->runScript($message);

        $response = [
            'executedCommand' => $result->getExecutedCommand(),
            'checkResult' => $result->getCheckResult(),
            'message' => $result->getMessage(),
            'note' => $result->getNote(),
            'rawScriptOutput' => $result->getRawScriptOutput(),
            'scriptOutput' => $result->getScriptOutput(),
        ];

        return $this->renderPage(
            'partials/check-script-test-output.html.twig',
            $response
        );
    }

    private function getScriptFile(int $id): string
    {
        $checkScript = $this->checkScriptRepository->find($id);
        if ($checkScript === null) {
            throw new \Exception('Check script not found');
        }
        return $checkScript->getFilename();
    }

    /** @return array<string, string> */
    private function getCheckScriptParameter(Request $request): array
    {
        $allParameters = $request->request->all();

        if (!isset($allParameters['check-script-parameter'])) {
            return [];
        }

        return [
            'checkScriptParameter' => $allParameters['check-script-parameter']
        ];
    }

    /** @param array<string, string> $config */
    private function getHostname(array $config): string 
    {
        return $config['hostname'] ?? 'localhost';
    }

    /** @param array<string, string> $config */
    private function getIpv4Address(array $config): string
    {
        return $config['ipv4'] ?? '127.0.0.1';
    }

    /** @param array<string, string> $config */
    private function getIpv6Address(array $config): string
    {
        return $config['ipv6'] ?? '::1';
    }
}
