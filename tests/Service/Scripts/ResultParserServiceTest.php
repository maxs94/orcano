<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Scripts;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
use App\DataObject\ScriptResultDataObject;
use App\Service\Scripts\ResultParserService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Service\Scripts\ResultParserService
 */
class ResultParserServiceTest extends TestCase
{
    private ResultParserService $service;

    public function setUp(): void
    {
        $this->service = new ResultParserService();
    }

    public function testODATAMissing(): void
    {
        $this->expectException(\Exception::class);
        $this->service->parse('INVALID RESULT STRING', new ConditionCollection());
    }

    public function testDecodeJsonError(): void
    {
        $this->expectException(\Exception::class);
        $this->service->parse('ODATA: {"INVALID JSON"}', new ConditionCollection());
    }

    public function testParseResultJson(): void
    {
        $jsonString = json_encode(['result' => 'test']);
        $result = $this->service->parse('ODATA: ' . $jsonString, new ConditionCollection());
        $this->assertEquals('test', $result->getMessage()['result']);
    }

    public function testParseResultJsonWithConditions(): void
    {
        $jsonString = json_encode(['result' => 'test', 'result2' => 'test']);

        $conditions = new ConditionCollection();
        $conditions->addCondition('result', new EqualsCondition('test'));
        $conditions->addCondition('result2', new EqualsCondition('error'));

        $result = $this->service->parse('ODATA: ' . $jsonString, $conditions);
        $this->assertEquals('test', $result->getMessage()['result']);
        $this->assertEquals(ScriptResultDataObject::RESULT_ERROR, $result->getResult());
    }

    public function testParseResultWithWarningCondition(): void
    {
        $jsonString = json_encode(['result' => 42]);

        $conditions = new ConditionCollection();
        $conditions->addCondition('result', new MinMaxCondition(0, 100, 40));

        $result = $this->service->parse('ODATA: ' . $jsonString, $conditions);
        $this->assertEquals(ScriptResultDataObject::RESULT_WARNING, $result->getResult());
    }

    public function testParseResultKeyNotFound(): void
    {
        $json_string = json_encode(['result' => 'test']);

        $conditions = new ConditionCollection();
        $conditions->addCondition('unknownKey', new EqualsCondition('test'));

        $this->expectException(\Exception::class);
        $this->service->parse('ODATA: ' . $json_string, $conditions);
    }
}
