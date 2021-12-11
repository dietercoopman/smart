<?php

$constraint = function ($constraint) {
    $constraint->aspectRatio();
};
return [
    'image'    => [
        'path'      => 'smart',
        'templates' => [
            'small' => [
                'resize' => [200, null, $constraint],
            ],
            'big'   => [
                'resize' => [500, null, $constraint],
            ]
        ]
    ],
    'download' => [
        'path' => 'smart/downloads',
        'default-text' => 'download this file'
    ]
];
