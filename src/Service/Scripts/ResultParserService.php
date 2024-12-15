<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\Condition\ConditionCollection;
use App\DataObject\ScriptResultDataObject;
use App\Exception\ArrayIsNullException;
use App\Exception\MissingKeyException;
use App\Service\DataTransformer\StringDataTransformer;

class ResultParserService
{
    /** @return array<string, mixed> */
    public function extractJson(string $result): array
    {
        $array = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        if ($array === null) {
            throw new ArrayIsNullException(sprintf('Could not decode json string: %s', $result));
        }

        return $this->transformAllKeys($array);
    }

    public function parseResultJson(ScriptResultDataObject $scriptResult, ConditionCollection $conditions): ScriptResultDataObject
    {
        $scriptResult->setMessage($scriptResult->getScriptOutput());

        foreach ($conditions->getConditions() as $conditionCollectionItem) {

            $condition = $conditionCollectionItem->getCondition();
            $oDataKey = $conditionCollectionItem->getName();

            $value = $scriptResult[$oDataKey] ?? null;
            if ($value !== null) {
                if ($condition->checkIfOk($value)) {
                    $scriptResult->setCheckResult(ScriptResultDataObject::RESULT_OK);
                } else {
                    $scriptResult->setCheckResult(ScriptResultDataObject::RESULT_ERROR);
                }

                if ($condition->checkIfWarn($value)) {
                    $scriptResult->setCheckResult(ScriptResultDataObject::RESULT_WARNING);
                }

                $scriptResult->setNote(sprintf('"%s" is %s', $oDataKey, $value));
            } else {
                throw new MissingKeyException(sprintf('Key "%s" not found in the script return result: %s', $oDataKey, json_encode($scriptResult, JSON_THROW_ON_ERROR)));
            }

            // if a check fails, we can stop here
            if ($scriptResult->getCheckResult() !== ScriptResultDataObject::RESULT_OK) {
                break;
            }
        }

        return $scriptResult;
    }

    /**
     * @param array<string, mixed> $array
     *
     * @return array<string, mixed>
     */
    private function transformAllKeys(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $key = StringDataTransformer::transformStringToLatin($key);
            $result[strtolower($key)] = $value;
        }

        return $result;
    }
}
