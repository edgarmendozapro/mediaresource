<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;

interface MediaResourceService {
    public function fromFile(UploadedFile $file, string $configKey): MediaResource;
    public function fromURL(string $url, string $configKey): MediaResource;
}
