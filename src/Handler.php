<?php

namespace EdgarMendozaTech\MediaResource;

interface Handler {
    public function process(string $path, string $configKey): MediaResource;
}

