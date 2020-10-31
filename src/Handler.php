<?php

namespace EdgarMendozaTech\MediaResource;

interface Handler {
    public function process(string $path, array $config): MediaResource;
}

