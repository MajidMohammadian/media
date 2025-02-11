<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class FileNotFoundException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('File not found!', $code, $previous);
    }
}
