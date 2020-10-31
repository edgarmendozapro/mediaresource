<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Support\Facades\Storage;

class ImageProcessor
{
    public function process(MediaResource $mediaResource): void
    {
        $tinyKey = config('media_resources.TINYPNG_KEY');

        if($tinyKey === null) {
            return;
        }

        $path = Storage::path("{$mediaResource->file_name}.{$mediaResource->file_extension}");
        \Tinify\setKey($tinyKey);
        \Tinify\fromFile($path)->toFile($path);
        $mediaResource->is_compressed = true;
        $mediaResource->save();
	}
}
