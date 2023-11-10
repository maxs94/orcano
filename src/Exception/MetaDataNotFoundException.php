<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Exception;

use Symfony\Component\Filesystem\Exception\IOException;

class MetaDataNotFoundException extends IOException
{
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null, string $path = null)
    {
        if (null === $message) {
            $message = null === $path ? 'Metadata could not be found.' : sprintf('Metadata could not be found in file "%s".', $path);
        }

        parent::__construct($message, $code, $previous, $path);
    }
}
