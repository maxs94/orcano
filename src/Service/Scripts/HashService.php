<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class HashService
{
    public function createHashFromFile(string $filename): string
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException(null, 0, null, $filename);
        }

        return md5_file($filename);
    }
}
