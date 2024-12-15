<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\MessageHandler;

use App\Condition\ConditionCollection;
use App\DataObject\ScriptResultDataObject;
use App\Entity\CheckResult;
use App\Entity\CheckScript;
use App\Message\CheckNotification;
use App\Message\CheckResultNotification;
use App\Repository\AssetServiceCheckRepository;
use App\Repository\CheckResultRepository;
use App\Service\Condition\ConditionService;
use App\Service\Scripts\ResultParserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckResultNotificationHandler
{
    public function __construct(
        private readonly ResultParserService $resultParserService,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckResultRepository $checkResultRepository,
        private readonly ConditionService $conditionService,
        private readonly LoggerInterface $logger
    ) {}

    public function __invoke(CheckResultNotification $message): void
    {
        $result = $message->getResult();
        $originalNotification = $message->getOriginalNotification();

        $conditions = $this->conditionService->getCheckConditions(
            $originalNotification->getAssetId(),
            $originalNotification->getAssetServiceCheckId()
        );

        $checkResult = $this->checkResult($result, $conditions);

        $this->logger->notice(sprintf('check %s on %s, result: %s (%s)',
            $originalNotification->getCheckScriptFilename(),
            $originalNotification->getHostname(),
            $checkResult->getCheckResult(),
            $checkResult->getNote()
        ));

        $checkResultEntity = $this->transformCheckResult($checkResult, $originalNotification);

        $serviceCheckName = $checkResultEntity->getServiceCheck()->getName();
        $checkScript = $checkResultEntity->getServiceCheck()->getCheckScript();

        if (!$checkScript instanceof CheckScript) {
            $this->logger->error(sprintf('Check script not found for service check %s', $serviceCheckName));
            return;
        }

        $this->entityManager->persist($checkResultEntity);
        $this->entityManager->flush();

        if ($checkScript->getName() === null) {
            $this->logger->error(sprintf('Check script name not found for service check %s.', $serviceCheckName));
            return;
        }

        $this->checkResultRepository->updateCheckResultTableStructure($checkResult, $checkScript->getName());
        $this->checkResultRepository->insertCheckResult($checkResult, $checkScript->getName(), $checkResultEntity->getId());
    }

    private function transformCheckResult(ScriptResultDataObject $scriptResult, CheckNotification $checkNotification): CheckResult
    {
        $checkResultEntity = new CheckResult();
        $checkResultEntity->setData([
            'result' => $scriptResult->getCheckResult(),
            'message' => json_encode($scriptResult->getMessage()),
            'scriptOutput' => json_encode($scriptResult->getScriptOutput()),
        ]);

        $assetServiceCheck = $this->assetServiceCheckRepository->find($checkNotification->getAssetServiceCheckId());
        if ($assetServiceCheck === null) {
            throw new \Exception('Asset service check not found');
        }

        $checkResultEntity->setAsset($assetServiceCheck->getAsset());
        $checkResultEntity->setServiceCheck($assetServiceCheck->getServiceCheck());
        $checkResultEntity->setAssetServiceCheck($assetServiceCheck);

        return $checkResultEntity;
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
