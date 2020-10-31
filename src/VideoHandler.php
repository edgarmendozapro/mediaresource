<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JamesHeinrich\GetID3;

class VideoHandler implements Handler
{
    public function process(string $path, string $configKey): MediaResource
    {
        $parts = explode('.', $path);
        if(count($parts) === 0) {
            throw new UnsupportedExtensionException(
                "Unknown file extension"
            );
        }

        $fileExtension = $parts[count($parts)-1];
        $fileName = FileNameGenerator::withoutFormat();
        $newPath = "{$fileName}.{$fileExtension}";

        Storage::move($path, $newPath);

        $getID3 = new GetID3;
        $video = $getID3->analyze($newPath);
        $duration = date('H:i:s.v', $video['playtime_seconds']);
        $width = $video['video']['resolution_x'];
        $height = $video['video']['resolution_y'];
        $fileSize = $video['filesize'];

        $url = Storage::url("{$fileName}.{$fileExtension}");

        return MediaResource::create([
            'file_name' => $fileName,
            'file_type' => 'video',
            'file_size' => $fileSize,
            'file_extension' => $fileExtension,
            'url' => $url,
            'width' => $width,
            'height' => $height,
        ]);
    }
}
