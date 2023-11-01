<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Trait;

use PHPUnit\Framework\TestCase;

trait GetterSetterTestingTrait
{
    public function testGettersSetters(): void
    {
        /** @var TestCase $test */
        $test = $this;

        $className = str_replace('App\\Tests\\', 'App\\', $test::class);
        $className = str_replace('Test', '', $className);

        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf('Class %s not found, cannot test getters/setters', $className));
        }

        $testedObject = $test
            ->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock()
        ;

        $refClass = new \ReflectionClass($className);
        $testableMethods = [];
        foreach ($refClass->getProperties() as $refProperty) {
            $propertyName = $refProperty->getName();
            $getterName = 'get' . ucfirst($propertyName);
            $setterName = 'set' . ucfirst($propertyName);
            if (!$refClass->hasMethod($getterName)) {
                continue;
            }
            if (!$refClass->hasMethod($setterName)) {
                continue;
            }
            $testableMethods[] = [$getterName, $setterName];
        }

        foreach ($testableMethods as [$getterName, $setterName]) {
            // skip auto testing if test method exists
            if (method_exists($this, 'test' . ucfirst($getterName))) {
                continue;
            }

            $refSetter = new \ReflectionMethod($className, $setterName);
            $refParams = $refSetter->getParameters();
            if (1 === count($refParams)) {
                $refParam = $refParams[0];
                $expectedValues = [];
                $expectedValues[] = $this->getParamMock($refParam->getType());
                if ($refParam->allowsNull() && null !== $refParam->getType()) {
                    $expectedValues[] = null;
                }
                foreach ($expectedValues as $expectedValue) {
                    $testedObject->{$setterName}($expectedValue);
                    $actualValue = $testedObject->{$getterName}();

                    $message = sprintf(
                        'Expected %s() value to equal %s (set using %s), got %s',
                        $getterName,
                        print_r($expectedValue, true),
                        $setterName,
                        print_r($actualValue, true)
                    );

                    $test->assertEquals($expectedValue, $actualValue, $message);
                }
            }
        }
    }

    private function getParamMock(\ReflectionNamedType $refType)
    {
        /** @var TestCase $test */
        $test = $this;

        $type = $refType->getName();
        if (interface_exists($type) && $type !== 'DateTimeInterface') {
            return $test->getMockBuilder($type)->getMockForAbstractClass();
        }
        if ($type === 'DateTimeInterface') {
            return new \DateTime();
        }

        return match ($type) {
            'NULL' => 'null',
            'bool' => (bool) random_int(0, 1),
            'int' => random_int(1, 100),
            'string' => str_shuffle('abcdefghijklmnopqrstuvxyz0123456789'),
            'array' => [],
            default => $this->getMockBuilder($type)
                ->disableOriginalConstructor()
                ->getMock(),
        };
    }
}
