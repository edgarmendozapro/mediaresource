<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Http\UploadedFile;

interface MediaResourceService {
    public function fromFile(UploadedFile $file, array $config): MediaResource;
    public function fromURL(string $url, array $config): MediaResource;
}
