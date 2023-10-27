<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\MessageHandler;

use App\DataObject\ScriptResultDataObject;
use App\Message\CheckNotification;
use App\Message\CheckResultNotification;
use App\Service\Scripts\ResultParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Process\Process;

#[AsMessageHandler]
class CheckNotificationHandler
{
    private const PROCESS_MAX_RUNTIME_SECONDS = 15;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly ResultParserService $resultParserService,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(CheckNotification $message): void
    {
        $this->runScript($message);
    }

    private function runScript(CheckNotification $message): bool
    {
        $scriptPath = $this->parameterBag->get('kernel.project_dir') . '/' . $message->getCheckScriptFilename();
        if (!file_exists($scriptPath)) {
            $this->sendError(sprintf('Script %s does not exist.', $scriptPath), $message);

            return false;
        }

        $command = sprintf('%s "%s" "%s" "%s"',
            $scriptPath,
            $message->getHostname(),
            $message->getIpv4Address(),
            $message->getIpv6Address()
        );

        $this->logger->notice(sprintf('CMD: %s', $command));

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(self::PROCESS_MAX_RUNTIME_SECONDS);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->sendError(sprintf('Script %s failed.', $scriptPath), $message);

            return false;
        }

        $output = $process->getOutput();

        $conditions = $message->getConditions();

        // todo: move result parsing to CheckResultNotificationHandler maybe?
        // then we would not have to send the Conditions along...
        // and the CheckNotificationHandler would only need to do one thing 
        try {
            $result = $this->resultParserService->parse($output, $conditions);
        } catch (\Exception $e) {
            $this->sendError(sprintf('ResultParserService failed: %s', $e->getMessage()), $message);

            return false;
        }

        $this->sendResultMessage($result, $message);

        return true;
    }

    private function sendError(string $message, CheckNotification $originalMessage): void
    {
        $result = new ScriptResultDataObject();
        $result->setResult(ScriptResultDataObject::RESULT_UNKNOWN);
        $result->setNote($message);

        $this->logger->error($message);

        $this->sendResultMessage($result, $originalMessage);
    }

    private function sendResultMessage(ScriptResultDataObject $result, CheckNotification $originalMessage): void
    {
        $message = new CheckResultNotification($result, $originalMessage);
        $this->bus->dispatch($message);
    }
}
