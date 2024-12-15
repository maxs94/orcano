<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\MessageHandler;

use App\DataObject\ScriptResultDataObject;
use App\Message\CheckNotification;
use App\Message\CheckResultNotification;
use App\Service\Scripts\ScriptRunnerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CheckNotificationHandler
{

    public function __construct(
        private readonly ScriptRunnerService $scriptRunnerService,
        private readonly MessageBusInterface $bus
    ) {}

    public function __invoke(CheckNotification $message): void
    {
        $this->runScript($message);
    }

    private function runScript(CheckNotification $message): bool
    {
        $result = $this->scriptRunnerService->runScript($message);
        $this->sendResultMessage($result, $message);

        return true;
    }

    private function sendResultMessage(ScriptResultDataObject $result, CheckNotification $originalMessage): void
    {
        $message = new CheckResultNotification($result, $originalMessage);
        $this->bus->dispatch($message);
    }

}
