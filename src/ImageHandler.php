<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Image;
use Intervention\Image\Exception\NotReadableException;

class ImageHandler implements Handler
{
    private $configSize;
    private $configThumbnails;
    private $configCompress;
    private $configFormat;

    public function process(string $path, array $config): MediaResource
    {
        $this->readConfig($config);

        $fileName = FileNameGenerator::withoutFormat();
        $mainImage = $this->createInterventionImage(
            $path,
            $this->configSize['width'],
            $this->configSize['height']
        );

        $this->save($fileName, $mainImage);
        $mediaResource = $this->createMediaResource(
            $fileName,
            $mainImage,
            null
        );
        $mediaResource->save();
        $this->compress($mediaResource);

        $mainMediaResourcePath = Storage::path(
            "{$mediaResource->file_name}.{$mediaResource->file_extension}"
        );
        foreach ($this->configThumbnails as $configThumbnail) {
            $thumbnailImage = $this->createInterventionImage(
                $mainMediaResourcePath,
                $configThumbnail['width'],
                $configThumbnail['height']
            );

            $thumbnailFileName = "{$fileName}_{$configThumbnail['suffix']}";
            $this->save($thumbnailFileName, $thumbnailImage);

            $thumbnailMediaResource = $this->createMediaResource(
                $thumbnailFileName,
                $thumbnailImage,
                $configThumbnail['alias']
            );
            $thumbnailMediaResource->mediaResource()->associate($mediaResource);
            $thumbnailMediaResource->save();

            $this->compress($thumbnailMediaResource);
        }

        return $mediaResource;
    }

    private function readConfig(array $config): void
    {
        $this->configSize = $config['size'];
        $this->configThumbnails = $config['thumbnails'];
        $this->configCompress = $config['compress'];
        $this->configFormat = $config['format'];
    }

    private function createInterventionImage(
        $path,
        ?int $width,
        ?int $height
    ): Image {
        $image = $this->resizeImage(
            ImageManagerStatic::make($path),
            $width,
            $height
        );

        if ($this->configFormat === "jpg") {
            $image = ImageManagerStatic::canvas(
                $image->width(),
                $image->height(),
                '#ffffff'
            )->insert($image);
        }

        return $image->encode($this->configFormat, 100);
    }

    // TODO: Add support for maxWidth and maxHeight
    private function resizeImage(Image $image, ?int $width, ?int $height): Image
    {
        if ($width != null && $height != null) {
            $image->fit($width, $height);
        } elseif ($width != null && $height == null) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } elseif ($width == null && $height != null) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        return $image;
    }

    private function createMediaResource(
        string $fileName,
        Image $image,
        ?string $alias
    ): MediaResource {
        $fullFileName = "{$fileName}.{$this->configFormat}";
        $url = Storage::url($fullFileName);

        $size = filesize(Storage::path($fullFileName));

        return new MediaResource([
            'file_name' => $fileName,
            'file_type' => 'image',
            'file_size' => $size,
            'file_extension' => $this->configFormat,
            'url' => $url,
            'width' => $image->width(),
            'height' => $image->height(),
            'alias' => $alias,
        ]);
    }

    private function save(string $fileName, Image $image): void
    {
        $path = "{$fileName}.{$this->configFormat}";
        Storage::put($path, $image);
    }

    private function compress(MediaResource $mediaResource): void
    {
        if ($this->configCompress) {
            ProcessMediaResource::dispatch($mediaResource);
        }
    }
}
