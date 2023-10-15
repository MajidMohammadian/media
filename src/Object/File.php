<?php

namespace JobMetric\Media\Object;

use Exception;
use JMedia;
use JobMetric\Media\Enums\MediaTypeEnum;
use JobMetric\Media\Events\UploadFileEvent;
use JobMetric\Media\Exceptions\CollectionNotFoundException;
use JobMetric\Media\Exceptions\DiskNotDefinedException;
use JobMetric\Media\Exceptions\DuplicateFileException;
use JobMetric\Media\Exceptions\DuplicateFileNameInLocationException;
use JobMetric\Media\Exceptions\FileMimeTypeIsNotAllowedException;
use JobMetric\Media\Exceptions\FileNotFoundException;
use JobMetric\Media\Exceptions\FileNotSendInRequestException;
use JobMetric\Media\Exceptions\FileSizeException;
use JobMetric\Media\Exceptions\FolderNotFoundException;
use JobMetric\Media\Helpers\DiskHelper;
use JobMetric\Media\Helpers\MediaPathHelper;
use JobMetric\Media\Helpers\ValidatorHelper;
use JobMetric\Media\Models\Media;
use JobMetric\Media\Models\MediaPath;
use Throwable;

class File
{
    private static File $instance;
    private array $file = [];

    /**
     * get instance object
     *
     * @return File
     */
    public static function getInstance(): File
    {
        if(empty(File::$instance)) {
            File::$instance = new File;
        }

        return File::$instance;
    }

    /**
     * set file
     *
     * @param Media $media
     *
     * @return File
     * @throws Throwable
     */
    public function setFile(Media $media): File
    {
        if($media->type != MediaTypeEnum::FILE->value) {
            throw new FileNotFoundException;
        }

        $this->file['name'] = $media->name;
        $this->file['disk'] = $media->disk;
        $this->file['filename'] = $media->filename;
        $this->file['mime_type'] = $media->mime_type;
        $this->file['size'] = $media->size;
        $this->file['content_id'] = $media->content_id;

        if(isset($media->additional['user_id'])) {
            $this->file['user_id'] = $media->additional['user_id'];
        }

        $this->file['is_responsive'] = false;
        if(in_array($this->file['mime_type'], config('jmedia.mime_type_responsive'))) {
            $this->file['is_responsive'] = true;
            if(isset($media->additional['responsive'])) {
                $this->file['responsive'] = $media->additional['responsive'];
            }
        }

        $this->file['collection'] = $media->collection;

        return $this;
    }

    /**
     * get file info
     *
     * @return array
     */
    public function getInfo(): array
    {
        return $this->file;
    }

    /**
     * file exist in folder
     *
     * @param string   $name
     * @param int|null $folder
     * @param string   $collection
     *
     * @return bool
     * @throws Throwable
     */
    public function exist(string $name, int $folder = null, string $collection = 'public'): bool
    {
        $disk = DiskHelper::getDiskByCollection($collection);

        if(!JMedia::folder()->exist($folder, $collection)) {
            throw new FolderNotFoundException;
        }

        if(Media::query()->where([
            'name'       => $name,
            'parent_id'  => $folder,
            'type'       => MediaTypeEnum::FILE->value,
            'disk'       => $disk,
            'collection' => $collection,
        ])->exists()) {
            return true;
        }

        return false;
    }

    /**
     * upload file
     *
     * @param int|null $folder
     * @param string   $collection
     * @param string   $field
     *
     * @return File
     * @throws Throwable
     */
    public function upload(int $folder = null, string $collection = 'public', string $field = 'file'): File
    {
        if(!request()->exists($field)) {
            throw new FileNotSendInRequestException($field);
        }

        $file = request()->file($field);

        $name = $file->getClientOriginalName();
        $mime_type = $file->getMimeType();
        $size = $file->getSize();
        $extension = $file->extension();
        $filename = uuid_create().'.'.$extension;

        ValidatorHelper::mimeType($mime_type);
        ValidatorHelper::fileSize($size);
        ValidatorHelper::collection($collection);

        $content_id = sha1($file->getContent());
        if(!config('jmedia.collections.'.$collection.'.duplicate_content')) {
            if(Media::query()->where([
                'collection' => $collection,
                'content_id' => $content_id
            ])->exists()) {
                throw new DuplicateFileException;
            }
        }

        if($this->exist($name, $folder, $collection)) {
            throw new DuplicateFileNameInLocationException;
        }

        $disk = DiskHelper::getDiskByCollection($collection);

        ValidatorHelper::nameInFolder($name, $folder, $collection);

        try {
            $file->storeAs($collection, $filename, $disk);
        } catch(Exception $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode());
        }

        $additional['user_id'] = auth()->check() ? auth()->id() : 0;

        /**
         * @var Media $object
         */
        $object = Media::query()->create([
            'name'       => $name,
            'parent_id'  => $folder,
            'type'       => MediaTypeEnum::FILE->value,
            'mime_type'  => $mime_type,
            'size'       => $size,
            'content_id' => $content_id,
            'additional' => $additional,
            'disk'       => $disk,
            'collection' => $collection,
            'filename'   => $filename,
        ]);

        $this->setFile($object);

        // Hierarchical Data Closure Table Pattern
        MediaPathHelper::store($object, $folder);

        event(new UploadFileEvent($object));

        return $this;
    }

    /**
     * download file
     *
     * @return void
     */
    public function download(): void
    {

    }

    /**
     * stream download file
     *
     * @return void
     */
    public function stream(): void
    {

    }

    /**
     * details file
     *
     * @return void
     */
    public function details(): void
    {

    }

    /**
     * use at in model
     *
     * @return void
     */
    public function useAt(): void
    {

    }

    /**
     * delete file
     *
     * @return void
     */
    public function delete(): void
    {

    }

    /**
     * rename category
     *
     * @return void
     */
    public function rename(): void
    {

    }
}
