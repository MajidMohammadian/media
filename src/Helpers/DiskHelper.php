<?php

namespace JobMetric\Media\Helpers;

use Throwable;

class DiskHelper
{
    /**
     * get disk by collection
     *
     * @param string $collection
     *
     * @return string
     * @throws Throwable
     */
    public static function getDiskByCollection(string $collection = 'public'): string
    {
        $disk = config('jmedia.collections.'.$collection.'.disk');

        ValidatorHelper::diskInFilesystem($disk);

        return $disk;
    }
}
