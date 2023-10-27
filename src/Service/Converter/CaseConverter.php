<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Converter;

class CaseConverter
{
    public static function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public static function snakeCaseToCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }

    public static function kebabCaseToCamelCase(string $string): string
    {
        return lcfirst(str_replace('-', '', ucwords($string, '-')));
    }

    public static function camelCaseToKebabCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $string));
    }
}
