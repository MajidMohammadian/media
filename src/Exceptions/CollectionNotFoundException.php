<?php

namespace JobMetric\Media\Exceptions;

use Exception;
use Throwable;

class CollectionNotFoundException extends Exception
{
    public function __construct(string $collection, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct('Collection "'.$collection.'" not found!', $code, $previous);
    }
}
