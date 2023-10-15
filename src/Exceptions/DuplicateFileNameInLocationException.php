<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class DuplicateFileNameInLocationException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('There is already a file with the same name in this location.', $code, $previous);
    }
}
