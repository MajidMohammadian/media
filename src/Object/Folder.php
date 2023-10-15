<?php

namespace JobMetric\Media\Object;

use JobMetric\Media\Enums\MediaTypeEnum;
use JobMetric\Media\Events\AddFolderEvent;
use JobMetric\Media\Exceptions\FileNotFoundException;
use JobMetric\Media\Exceptions\FolderExistException;
use JobMetric\Media\Exceptions\FolderNotFoundException;
use JobMetric\Media\Helpers\DiskHelper;
use JobMetric\Media\Helpers\MediaPathHelper;
use JobMetric\Media\Helpers\ValidatorHelper;
use JobMetric\Media\Models\Media;
use Throwable;

class Folder
{
    private static Folder $instance;
    private array $folder = [];

    public function __construct()
    {
    }

    /**
     * get instance object
     *
     * @return Folder
     */
    public static function getInstance(): Folder
    {
        if(empty(Folder::$instance)) {
            Folder::$instance = new Folder;
        }

        return Folder::$instance;
    }

    /**
     * set folder
     *
     * @param Media $media
     *
     * @return Folder
     * @throws Throwable
     */
    public function setFolder(Media $media): Folder
    {
        if($media->type != MediaTypeEnum::FOLDER->value) {
            throw new FileNotFoundException;
        }

        $this->folder['name'] = $media->name;
        $this->folder['disk'] = $media->disk;

        $this->folder['icon'] = 'default';
        if(isset($media->additional['icon'])) {
            $this->folder['icon'] = $media->additional['icon'];
        }

        $this->folder['collection'] = $media->collection;

        return $this;
    }

    /**
     * get folder info
     *
     * @return array
     */
    public function getInfo(): array
    {
        return $this->folder;
    }

    /**
     * exist folder
     *
     * @param int|null $folder
     * @param string   $collection
     *
     * @return bool
     * @throws Throwable
     */
    public function exist(int $folder = null, string $collection = 'public'): bool
    {
        $disk = DiskHelper::getDiskByCollection($collection);

        if($folder == null) {
            return true;
        }

        if(Media::query()->where([
            'id'         => $folder,
            'type'       => MediaTypeEnum::FOLDER->value,
            'disk'       => $disk,
            'collection' => $collection,
        ])->exists()) {
            return true;
        }

        return false;
    }

    /**
     * add category
     *
     * @param string   $name
     * @param int|null $folder
     * @param string   $collection
     *
     * @return Folder
     * @throws Throwable
     */
    public function add(string $name, int $folder = null, string $collection = 'public'): Folder
    {
        ValidatorHelper::collection($collection);

        if($this->exist($folder, $collection)) {
            throw new FolderExistException;
        }

        $disk = DiskHelper::getDiskByCollection($collection);

        ValidatorHelper::nameInFolder($name, $folder, $collection);

        $additional['user_id'] = auth()->check() ? auth()->id() : 0;

        /**
         * @var Media $object
         */
        $object = Media::query()->create([
            'name'       => $name,
            'parent_id'  => $folder,
            'type'       => MediaTypeEnum::FOLDER->value,
            'additional' => $additional,
            'disk'       => $disk,
            'collection' => $collection,
        ]);

        $this->setFolder($object);

        // Hierarchical Data Closure Table Pattern
        MediaPathHelper::store($object, $folder);

        event(new AddFolderEvent($object));

        return $this;
    }

    /**
     * delete category
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
