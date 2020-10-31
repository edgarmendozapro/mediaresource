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

    protected $hidden = [
        'file_name',
        'file_size',
        'file_extension',
        'alias',
        'duration',
        'is_compressed',
        'created_at',
        'media_resource_id',
        'id',
        'pivot',
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
