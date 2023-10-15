<?php

namespace JobMetric\Media\Helpers;

use JobMetric\Media\Exceptions\CollectionNotFoundException;
use JobMetric\Media\Exceptions\DiskNotDefinedException;
use JobMetric\Media\Exceptions\FileMimeTypeIsNotAllowedException;
use JobMetric\Media\Exceptions\FileSizeException;
use JobMetric\Media\Exceptions\NameIsRepeatedException;
use JobMetric\Media\Models\Media;
use Throwable;

class ValidatorHelper
{
    /**
     * validation disk in filesystem
     *
     * @param string $disk
     *
     * @return void
     * @throws Throwable
     */
    public static function diskInFilesystem(string $disk): void
    {
        if(!array_key_exists($disk, config('filesystems.disks'))) {
            throw new DiskNotDefinedException($disk);
        }
    }

    /**
     * validation mime type
     *
     * @param string $mime_type
     *
     * @return void
     * @throws Throwable
     */
    public static function mimeType(string $mime_type): void
    {
        if(!in_array($mime_type, config('jmedia.mime_type'))) {
            throw new FileMimeTypeIsNotAllowedException;
        }
    }

    /**
     * validation file size
     *
     * @param int $size
     *
     * @return void
     * @throws Throwable
     */
    public static function fileSize(int $size): void
    {
        if($size > config('jmedia.file_max_size')) {
            throw new FileSizeException;
        }
    }

    /**
     * validation collection
     *
     * @param string $collection
     *
     * @return void
     * @throws Throwable
     */
    public static function collection(string $collection): void
    {
        if(!array_key_exists($collection, config('jmedia.collections'))) {
            throw new CollectionNotFoundException($collection);
        }
    }

    /**
     * check name in folder
     *
     * @param string   $name
     * @param int|null $folder
     * @param string   $collection
     *
     * @return void
     * @throws Throwable
     */
    public static function nameInFolder(string $name, int $folder = null, string $collection = 'public'): void
    {
        $disk = DiskHelper::getDiskByCollection($collection);

        $params = [
            'name'       => $name,
            'disk'       => $disk,
            'collection' => $collection,
        ];

        if($folder != null) {
            $params['id'] = $folder;
        }

        if(Media::query()->where($params)->exists()) {
            throw new NameIsRepeatedException;
        }
    }
}
