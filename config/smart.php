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
        ],
        'file-not-found' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAA1JREFUGFdj+P///38ACfsD/QVDRcoAAAAASUVORK5CYII='
    ],
    'download' => [
        'path'         => 'smart/downloads',
        'default-text' => 'download this file'
    ]
];
