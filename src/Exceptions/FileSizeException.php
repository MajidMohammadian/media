<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class FileSizeException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('The file size is greater than '.config('jmedia.file_max_size').' bytes!', $code, $previous);
    }
}
