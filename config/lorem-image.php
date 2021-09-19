<?php

return [

    'default' => [
        'driver' => 'color-block',
    ],

    'url' => [
        'prefix'      => 'limsum',
        'name_prefix' => 'limsum',
    ],

    'drivers' => [
        'color-block'  => [
            'extensions' => [
                'svg',
                'png',
                'jpg',
            ],
            'default'    => [
                'width'     => 100,
                'height'    => 100,
                'color'     => '#808080',
                'extension' => 'svg',
            ],
            'types'      => [
                /*'thumbnail'    => [
                    'width'      => 265,
                    'height'     => 170,
                    'color'      => '#123456',
                    'extension' => 'jpg',
                ],*/
            ],
        ],
        'lorem-picsum' => [
            'default' => [
                'width'     => 100,
                'height'    => 100,
                'extension' => false,
                'grayscale' => false,
                'blur'      => false,
                'random'    => false,
                'seed'      => false,
                'id'        => false,
            ],
            'types'   => [
                'thumbnail'    => [
                    'width'      => 265,
                    'height'     => 170,
                    'random'     => true,
                ],
            ],
        ],
    ],
];
