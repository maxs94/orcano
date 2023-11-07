<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class ODataStringNotFoundException extends Exception
{

    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null, string $path = null)
    {
       parent::__construct($message, $code, $previous, $path); 
    }

}