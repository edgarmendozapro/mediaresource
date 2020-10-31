<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Database\Eloquent\Model;

class MediaResource extends Model
{
    protected $table = 'media_resources';

    protected $fillable = [
        'file_name',
        'file_type',
        'file_size',
        'file_extension',
        'url',
        'width',
        'height',
        'alias',
        'duration',
    ];

    protected $with = [
        'mediaResources',
    ];

    public $timestamps = false;

    public function mediaResource()
    {
        return $this->belongsTo(MediaResource::class);
    }

    public function mediaResources()
    {
        return $this->hasMany(MediaResource::class);
    }
}
