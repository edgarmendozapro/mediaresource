<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use EdgarMendozaTech\MediaResource\Exception\UnsupportedExtensionException;

class MediaResourceServiceDefaultImpl implements MediaResourceService
{
    private $handler;

    public function fromFile(
        UploadedFile $file,
        array $config
    ): MediaResource {
        $this->handler = $this->getHandlerImplementationFromExtension($file->clientExtension());

        $pathTmpFile = $file->store('/');

        $filePath = storage_path("app/public/{$pathTmpFile}");

        $mediaResource = $this->handler->process($filePath, $config);

        Storage::delete($pathTmpFile);

        return $mediaResource;
    }

    public function fromURL(string $url, array $config): MediaResource
    {
        // TODO: Extract file extension from url.
        // Hardcoded to jpg for now.
        $this->handler = $this->getHandlerImplementationFromExtension('jpg');
        return $this->handler->process($url, $config);
    }

    private function getHandlerImplementationFromExtension(string $ext): Handler
    {
        switch (strtolower($ext)) {
            case "jpg":
            case "jpeg":
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
