<?php

declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Collection;

use App\DataObject\DataObjectInterface;

interface DataObjectCollectionInterface extends \Iterator
{
    public function add(DataObjectInterface $dataObject, string $key = null): void;

    public function unset(string $key): void;

    public function get(string $key): ?DataObjectInterface;

    public function getFirst(): ?DataObjectInterface;

    /** @return array<DataObjectInterface> */
    public function getElements(): array;

    /** @return array<mixed> */
    public function getColumn(string $column): array;

    public function getCount(): int;

    /**
     * @param array<DataObjectInterface> $haystack
     *
     * @return array<DataObjectInterface>
     */
    public function findBy(string $property, string $value, array $haystack = null): array;

    public function findOneBy(string $property, string $value): ?DataObjectInterface;

    /** @param array<string, string> $searchFilter */
    public function filter(array $searchFilter): DataObjectCollectionInterface;

    /** @return array<string> */
    public function getKeys(): array;

    /** @param int|string $key */
    public function keyExists(mixed $key): bool;

    public function rewind(): void;

    public function valid(): bool;

    public function mergeInto(DataObjectCollectionInterface $obj): void;

    /** @return string|int|null */
    public function key(): mixed;

    public function next(): void;

    /** @return bool|DataObjectInterface */
    public function current(): mixed;
}
