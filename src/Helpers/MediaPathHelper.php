<?php

namespace JobMetric\Media\Helpers;

use JobMetric\Media\Models\Media;
use JobMetric\Media\Models\MediaPath;

class MediaPathHelper
{
    /**
     * store media path
     *
     * @param Media    $media
     * @param int|null $folder
     *
     * @return void
     */
    public static function store(Media $media, int|null $folder): void
    {
        // Hierarchical Data Closure Table Pattern
        $level = 0;

        $paths = MediaPath::query()->where('media_id', $folder)->orderBy('level')->get();
        foreach($paths as $path) {
            MediaPath::query()->create([
                'media_id' => $folder,
                'path_id'  => $path->path_id,
                'level'    => $level++
            ]);
        }

        MediaPath::query()->create([
            'media_id' => $media->id,
            'path_id'  => $media->id,
            'level'    => $level
        ]);
    }
}
