<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\MessageHandler;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
use App\DataObject\ScriptResultDataObject;
use App\Message\CheckResultNotification;
use App\Service\Condition\ConditionService;
use App\Service\Scripts\ResultParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckResultNotificationHandler
{
    public function __construct(
        private readonly ResultParserService $resultParserService,
        private readonly ConditionService $conditionService,
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(CheckResultNotification $message): void
    {
        $result = $message->getResult();
        $originalNotification = $message->getOriginalNotification();

        // todo:
        //
        // have specific conditions per checkscript
        //
        // have an inheritance would make very much sense:
        // AssetGroup -> Asset
        // $conditionTemplateString = // serialized conditions string
        // $conditions = unserialize($conditionTemplateString);
        /*    $conditions = new ConditionCollection();
            $conditions->addCondition('result', new EqualsCondition(0));
            $conditions->addCondition('time', new MinMaxCondition(0, 1000, 20));  // ok if between 0 and 1000, warn if between 20 and 1000

            echo addslashes(serialize($conditions));*/

        $conditions = $this->conditionService->getCheckConditions(
            $originalNotification->getAssetId(),
            $originalNotification->getServiceCheckId()
        );

        $checkResult = $this->checkResult($result, $conditions);

        $this->logger->notice(sprintf('check %s on %s, result: %s (%s)',
            $originalNotification->getCheckScriptFilename(),
            $originalNotification->getHostname(),
            $checkResult->getCheckResult(),
            $checkResult->getNote()
        ));
    }

    private function checkResult(ScriptResultDataObject $result, ConditionCollection $conditions): ScriptResultDataObject
    {
        $output = $result->getScriptOutput();

        try {
            $result = $this->resultParserService->parseResultJson($output, $conditions);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('ResultParserService failed: %s', $e->getMessage()), $output);
            $result->setCheckResult(ScriptResultDataObject::RESULT_UNKNOWN);
        }

        return $result;
    }
}
