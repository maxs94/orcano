<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\Condition\AbstractCondition;
use App\Condition\ConditionCollection;
use App\DataObject\ScriptResultDataObject;

class ResultParserService
{
    public const ODATA_STRING = 'ODATA:';

    public function parse(string $result, ConditionCollection $conditions): ScriptResultDataObject
    {
        if (!stristr($result, self::ODATA_STRING)) {
            throw new \Exception('Result does not start with ODATA: string');
        }

        $jsonString = substr($result, strpos($result, self::ODATA_STRING) + strlen(self::ODATA_STRING));

        $array = json_decode($jsonString, true);

        if ($array === null) {
            throw new \Exception(sprintf('Could not decode json string: %s', $jsonString));
        }

        $converted = $this->convertAllKeysToLowerCase($array);

        return $this->parseResultJson($converted, $conditions);
    }

    /**
     * @param array<string, mixed> $scriptResult
     */
    private function parseResultJson(array $scriptResult, ConditionCollection $conditions): ScriptResultDataObject
    {
        $result = new ScriptResultDataObject();
        $result->setMessage($scriptResult);

        /** @var AbstractCondition $condition */
        foreach ($conditions->getConditions() as $key => $condition) {
            $value = $scriptResult[$key] ?? null;
            if ($value !== null) {
                if ($condition->checkIfOk($value)) {
                    $result->setResult(ScriptResultDataObject::RESULT_OK);
                } else {
                    $result->setResult(ScriptResultDataObject::RESULT_ERROR);
                }

                if ($condition->checkIfWarn($value)) {
                    $result->setResult(ScriptResultDataObject::RESULT_WARNING);
                }

                $result->setNote(sprintf('"%s" is %s', $key, $value));
            } else {
                throw new \Exception(sprintf('Key "%s" not found in script "%s" return result: %s', $key, $scriptResult['name'], json_encode($scriptResult)));
            }

            // if a check fails, we can stop here
            if ($result->getResult() !== ScriptResultDataObject::RESULT_OK) {
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
