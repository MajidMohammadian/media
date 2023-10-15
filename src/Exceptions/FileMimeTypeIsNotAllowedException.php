<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class FileMimeTypeIsNotAllowedException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('File mime type is not allowed!!', $code, $previous);
    }
}
