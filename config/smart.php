<?php

return [
    'image'    => [
        'path'      => 'smart',
        'templates' => [
            'small' => [
                'resize' => [200, null, ['aspectRatio']],
            ],
            'big'   => [
                'resize' => [500, null, ['aspectRatio']],
            ]
        ]
    ],
    'download' => [
        'path'         => 'smart/downloads',
        'default-text' => 'download this file'
    ]
];
