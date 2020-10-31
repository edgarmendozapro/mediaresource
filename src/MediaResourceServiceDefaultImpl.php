<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use EdgarMendozaTech\MediaResource\Exception;

class MediaResourceServiceDefaultImpl implements MediaResourceService
{
    private $handler;

    public function fromFile(
        UploadedFile $file,
        string $configKey
    ): MediaResource {
        $fileType = $this->getFileTypeFromExtension($file->clientExtension());
        $this->handler = $this->getHandlerImplementation($fileType);

        $pathTmpFile = $file->store('/');
        $filePath = storage_path("app/public/{$pathTmpFile}");

        $mediaResource = $this->handler->process($filePath, $configKey);

        Storage::delete($pathTmpFile);

        return $mediaResource;
    }

    public function fromURL(string $url, string $configKey): MediaResource
    {
        $fileType = $this->getFileTypeFromExtension('jpg');
        $this->handler = $this->getHandlerImplementation($fileType);
        return $this->handler->process($url, $configKey);
    }

    private function getFileTypeFromExtension(string $ext): string
    {
        switch (strtolower($ext)) {
            case "jpg":
            case "png":
                return "image";
            case "mp4":
            case "mpeg":
            case "mkv":
            case "avi":
            case "mov":
                return "video";
            default:
                throw new UnsupportedExtensionException(
                    "'{$ext}' file extension is not supported."
                );
        }
    }

    private function getHandlerImplementation(string $fileType): Handler
    {
        switch($fileType) {
            case "image":
                return new ImageHandler();
            case "video":
                return new VideoHandler();
            default:
                throw new UnsupportedTypeException(
                    "'{$fileType}' type is not supported."
                );
        }
    }
}
