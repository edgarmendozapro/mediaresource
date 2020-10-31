<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Support\Str;

class FileNameGenerator
{
    public static function withoutFormat(): string
    {
        return self::generate();
    }

    public static function withFormat(string $format): string
    {
        return self::generate() . ".{$format}";
    }

    private static function generate(): string
    {
        return (string) Str::uuid();
    }
}
