<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class NameIsRepeatedException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('A file or folder with the same name as you specified already exists. Specify another name.', $code, $previous);
    }
}
