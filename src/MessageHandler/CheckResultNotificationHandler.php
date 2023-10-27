<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\MessageHandler;

use App\Message\CheckResultNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckResultNotificationHandler
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(CheckResultNotification $message): void
    {
        $result = $message->getResult();
        $originalNotification = $message->getOriginalNotification();

        $this->logger->notice(sprintf('result: %s %s (%s)', $result->getResult(), $result->getNote(), $originalNotification->getHostname()));
    }
}
