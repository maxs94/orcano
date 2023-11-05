<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity\Trait;

use Symfony\Component\Serializer\Annotation\Ignore;

trait SetDataTrait
{
    /** @param array<string, mixed> $data */
    #[Ignore]
    private function setDataIfNotEmptyBoolean(array $data, string $key, string $property): void
    {
        if (!array_key_exists($key, $data)) {
            return;
        }
        if ($data[$key] === null) {
            return;
        }

        $value = $data[$key];

        if ($value === 'on' || $value === 'true' || $value === '1' || $value === 1 || $value === true) {
            $this->{$property} = true;

            return;
        }

        $this->{$property} = false;
    }

    /** @param array<string, mixed> $data */
    #[Ignore]
    private function setDataIfNotEmptyInteger(array $data, string $key, string $property): void
    {
        if (!array_key_exists($key, $data)) {
            return;
        }
        if ($data[$key] === null) {
            return;
        }
        $this->{$property} = (int) $data[$key];
    }

    /** @param array<string, mixed> $data */
    #[Ignore]
    private function setDataIfNotEmptyString(array $data, string $key, string $property): void
    {
        if (!array_key_exists($key, $data)) {
            return;
        }
        if ($data[$key] === null) {
            return;
        }
        $this->{$property} = (string) $data[$key];
    }

    /** @param array<string, mixed> $data */
    #[Ignore]
    private function setDataIfNotEmptyFloat(array $data, string $key, string $property): void
    {
        if (!array_key_exists($key, $data)) {
            return;
        }
        if ($data[$key] === null) {
            return;
        }
        $this->{$property} = (float) $data[$key];
    }
}
