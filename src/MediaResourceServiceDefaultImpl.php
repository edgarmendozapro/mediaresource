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
        $this->handler = $this->getHandlerImplementation($file->clientExtension());

        $pathTmpFile = $file->store('/');

        $filePath = storage_path("app/public/{$pathTmpFile}");

        $mediaResource = $this->handler->process($filePath, $configKey);

        Storage::delete($pathTmpFile);

        return $mediaResource;
    }

    public function fromURL(string $url, string $configKey): MediaResource
    {
        // TODO: Extract file extension from url.
        // Hardcoded to jpg for now.
        $this->handler = $this->getHandlerImplementation('jpg');
        return $this->handler->process($url, $configKey);
    }

    private function getHandlerImplementationFromExtension(string $ext): Handler
    {
        switch (strtolower($ext)) {
            case "jpg":
            case "png":
                return new ImageHandler();
            case "mp4":
            case "mpeg":
            case "mkv":
            case "avi":
            case "mov":
                return new VideoHandler();
            default:
                throw new UnsupportedExtensionException(
                    "'{$ext}' file extension is not supported."
                );
        }
    }
}
