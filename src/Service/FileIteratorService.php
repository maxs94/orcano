<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service;

/**
 * @copyright 2023 MSIT (https://markus-steindl.de)
 */
class FileIteratorService
{
    public function findFilesWithExtension(string $path, string $extension = '.jpg'): \Generator
    {
        if (!is_dir($path)) {
            throw new \Exception('${path} is not a directory');
        }

        $directoryIterator = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directoryIterator);

        $regex = sprintf('/\%s$/', $extension);

        $regexIterator = new \RegexIterator($iterator, $regex, \RecursiveRegexIterator::GET_MATCH);

        yield from $regexIterator;
    }
}
