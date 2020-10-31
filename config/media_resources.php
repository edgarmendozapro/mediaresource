<?php

return [
    'TINYPNG_KEY' => env('TINYPNG_KEY', null),
    'media_resources' => [
        'post_cover' => [
            'size' => [
                'width' => 1200,
                'height' => null,
            ],
            'format' => 'jpg',
            'thumbnails' => [
                [
                    'width' => 600,
                    'height' => null,
                    'suffix' => '600x',
                    'alias' => 'post_cover_md',
                ],
            ],
            'compress' => false,
        ],
    ],
];
