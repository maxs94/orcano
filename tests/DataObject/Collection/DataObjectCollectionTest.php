<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\DataObject\Collection;

use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\DataObjectInterface;
use App\Tests\DataObject\TestDataObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class DataObjectCollectionTest extends TestCase
{
    private DataObjectCollectionInterface $collection;

    public function setUp(): void
    {
        $do1 = new TestDataObject('do1');
        $do2 = new TestDataObject('do2');

        $this->collection = new DataObjectCollection();
        $this->collection->add($do1, 'KEY_DO1');
        $this->collection->add($do2, 'KEY_DO2');
    }

    public function testCount(): void
    {
        $this->assertEquals(2, $this->collection->getCount());
    }

    public function testCurrent(): void
    {
        /** @var TestDataObject $current */
        $current = $this->collection->current();
        $this->assertInstanceOf(DataObjectInterface::class, $current);
        $this->assertSame('do1', $current->getName());
    }

    public function testNext(): void
    {
        $this->collection->next();
        /** @var TestDataObject $current */
        $current = $this->collection->current();
        $this->assertInstanceOf(DataObjectInterface::class, $current);
        $this->assertSame('do2', $current->getName());
    }

    public function testKey(): void
    {
        $this->assertSame('KEY_DO1', $this->collection->key());
        $this->collection->next();
        $this->assertSame('KEY_DO2', $this->collection->key());
    }

    public function testValid(): void
    {
        $this->assertTrue($this->collection->valid());
        $this->collection->next();
        $this->assertTrue($this->collection->valid());
        $this->collection->next();
        $this->assertFalse($this->collection->valid());
    }

    public function testRewind(): void
    {
        $this->collection->next();
        $this->collection->rewind();
        $this->assertSame('KEY_DO1', $this->collection->key());
    }

    public function testMergeInto(): void
    {
        $newCollection = new DataObjectCollection();
        $do3 = new TestDataObject('do3');
        $newCollection->add($do3, 'KEY_DO3');
        $this->collection->mergeInto($newCollection);
        $this->assertSame(3, $this->collection->getCount());
    }

    public function testGet(): void
    {
        $this->assertInstanceOf(DataObjectInterface::class, $this->collection->get('KEY_DO1'));
        $this->assertNull($this->collection->get('KEY_DO3'));
    }

    public function testGetFirst(): void
    {
        /** @var TestDataObject $first */
        $first = $this->collection->getFirst();
        $this->assertSame('do1', $first->getName());
    }

    public function testGetElements(): void
    {
        $elements = $this->collection->getElements();
        $this->assertCount(2, $elements);
        $this->assertInstanceOf(DataObjectInterface::class, $elements['KEY_DO1']);
        $this->assertInstanceOf(DataObjectInterface::class, $elements['KEY_DO2']);
    }

    public function testGetColumn(): void
    {
        $this->assertSame(['do1', 'do2'], $this->collection->getColumn('name'));
    }

    public function testeUnset(): void
    {
        $this->collection->unset('KEY_DO1');
        $this->assertSame(1, $this->collection->getCount());
    }

    public function testFindBy(): void
    {
        $results = $this->collection->findBy('name', 'do2');
        $this->assertCount(1, $results);
        $this->assertSame('KEY_DO2', key($results));
    }

    public function testFindOneBy(): void
    {
        /** @var TestDataObject $result */
        $result = $this->collection->findOneBy('name', 'do2');
        $this->assertInstanceOf(DataObjectInterface::class, $result);
        $this->assertSame('do2', $result->getName());
    }

    public function testFilter(): void
    {
        $results = $this->collection->filter(['name' => 'do2']);
        $this->assertInstanceOf(DataObjectCollectionInterface::class, $results);
        $this->assertSame(1, $results->getCount());
        /** @var TestDataObject $result */
        $result = $results->getFirst();
        $this->assertInstanceOf(DataObjectInterface::class, $result);
        $this->assertSame('do2', $result->getName());
    }

    public function testGetKeys(): void
    {
        $this->assertSame(['KEY_DO1', 'KEY_DO2'], $this->collection->getKeys());
    }

    public function testKeyExists(): void
    {
        $this->assertTrue($this->collection->keyExists('KEY_DO1'));
        $this->assertFalse($this->collection->keyExists('KEY_DO3'));
    }
}
