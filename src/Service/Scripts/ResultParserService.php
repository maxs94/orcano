<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\Condition\AbstractCondition;
use App\Condition\ConditionCollection;
use App\DataObject\ScriptResultDataObject;
use App\Exception\ArrayIsNullException;
use App\Exception\MissingKeyException;
use App\Exception\ODataStringNotFoundException;

class ResultParserService
{
    public const ODATA_STRING = 'ODATA:';

    /** @return array<string, mixed> */
    public function extractJson(string $result): array
    {
        if (!stristr($result, self::ODATA_STRING)) {
            throw new ODataStringNotFoundException('Result does not start with ODATA: string');
        }

        $jsonString = substr($result, strpos($result, self::ODATA_STRING) + strlen(self::ODATA_STRING));

        $array = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

        if ($array === null) {
            throw new ArrayIsNullException(sprintf('Could not decode json string: %s', $jsonString));
        }

        return $this->convertAllKeysToLowerCase($array);
    }

    /**
     * @param array<string, mixed> $scriptResult
     */
    public function parseResultJson(array $scriptResult, ConditionCollection $conditions): ScriptResultDataObject
    {
        $result = new ScriptResultDataObject();
        $result->setMessage($scriptResult);

        /** @var AbstractCondition $condition */
        foreach ($conditions->getConditions() as $key => $condition) {
            $value = $scriptResult[$key] ?? null;
            if ($value !== null) {
                if ($condition->checkIfOk($value)) {
                    $result->setCheckResult(ScriptResultDataObject::RESULT_OK);
                } else {
                    $result->setCheckResult(ScriptResultDataObject::RESULT_ERROR);
                }

                if ($condition->checkIfWarn($value)) {
                    $result->setCheckResult(ScriptResultDataObject::RESULT_WARNING);
                }

                $result->setNote(sprintf('"%s" is %s', $key, $value));
            } else {
                throw new MissingKeyException(sprintf('Key "%s" not found in the script return result: %s', $key, json_encode($scriptResult, JSON_THROW_ON_ERROR)));
            }

            // if a check fails, we can stop here
            if ($result->getCheckResult() !== ScriptResultDataObject::RESULT_OK) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $array
     *
     * @return array<string, mixed>
     */
    private function convertAllKeysToLowerCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[strtolower($key)] = $value;
        }

        return $result;
    }
}
