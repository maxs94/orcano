<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Collection;

use App\DataObject\DataObjectInterface;

class DataObjectCollection implements DataObjectCollectionInterface
{
    /** @var array<DataObjectInterface> */
    private array $objects;

    /**
     * @param array<DataObjectInterface> $dataObjects
     */
    public function __construct(array $dataObjects = [], string $indexBy = null)
    {
        if ($indexBy !== null) {
            $this->indexBy($dataObjects, $indexBy);
        }

        $this->objects = $dataObjects;
    }

    public function current(): ?DataObjectInterface
    {
        return current($this->objects);
    }

    public function next(): void
    {
        next($this->objects);
    }

    public function key(): ?string
    {
        if (key($this->objects) === null) {
            return null;
        }

        return (string) key($this->objects);
    }

    public function valid(): bool
    {
        return key($this->objects) !== null;
    }

    public function rewind(): void
    {
        reset($this->objects);
    }

    public function mergeInto(DataObjectCollectionInterface $obj): void
    {
        $this->objects = array_merge($this->objects, $obj->getElements());
    }

    public function add(DataObjectInterface $dataObject, string $key = null): void
    {
        if (is_null($key)) {
            $this->objects[] = $dataObject;

            return;
        }

        $this->objects[$key] = $dataObject;
    }

    public function get(string $key): ?DataObjectInterface
    {
        if (array_key_exists($key, $this->objects)) {
            return $this->objects[$key];
        }

        return null;
    }

    public function getFirst(): ?DataObjectInterface
    {
        $entry = reset($this->objects);

        if ($entry instanceof DataObjectInterface) {
            return $entry;
        }

        return null;
    }

    public function getElements(): array
    {
        return $this->objects;
    }

    public function getColumn(string $column): array
    {
        $getter = 'get' . ucfirst($column);

        $firstObject = $this->getFirst();

        if ($firstObject === null) {
            return [];
        }

        $objectClass = get_class($firstObject);

        if (!method_exists($objectClass, $getter)) {
            throw new \Exception('Object ' . $objectClass . ' does not have method ' . $getter);
        }

        $results = [];
        foreach ($this->objects as $object) {
            $val = $object->{$getter}();
            if (!empty($val)) {
                $results[] = $object->{$getter}();
            }
        }

        return $results;
    }

    public function getCount(): int
    {
        return count($this->objects);
    }

    public function unset(string $key): void
    {
        unset($this->objects[$key]);
    }

    public function findOneBy(string $property, string $value): ?DataObjectInterface
    {
        $result = $this->findBy($property, $value);
        if (count($result) > 0) {
            return reset($result);
        }

        return null;
    }

    public function findBy(string $property, string $value, array $haystack = null): array
    {
        $result = [];
        $objects = $haystack ?? $this->objects;

        foreach ($objects as $key => $object) {
            $getter = 'get' . ucfirst($property);
            if (method_exists($object, $getter) && $object->{$getter}() === $value) {
                $result[$key] = $object;
                continue;
            }

            if (isset($object->{$property}) && $object->{$property} === $value) {
                $result[$key] = $object;
                continue;
            }
        }

        return $result;
    }

    public function filter(array $searchFilter): DataObjectCollectionInterface
    {
        $objects = $this->objects;

        foreach ($searchFilter as $property => $value) {
            $objects = $this->findBy($property, $value, $objects);
        }

        return new DataObjectCollection($objects);
    }

    public function getKeys(): array
    {
        return array_keys($this->objects);
    }

    public function keyExists($key): bool
    {
        return array_key_exists($key, $this->objects);
    }

    /** @param array<DataObjectInterface> $dataObjects */
    private function indexBy(array &$dataObjects, string $indexBy): void
    {
        $indexed = [];

        foreach ($dataObjects as $dataObject) {
            $getter = 'get' . ucfirst($indexBy);
            if (!method_exists($dataObject, $getter)) {
                throw new \Exception('Object ' . get_class($dataObject) . ' does not have method ' . $getter);
            }

            $indexed[$dataObject->{$getter}()] = $dataObject;
        }

        $dataObjects = $indexed;
    }
}
