<?php

return [
    'askDefault' => [
        'php' => 'php',
        'dir' => __DIR__ . '/../../',
        'firstCode' => '',
    ],

    /**
     * your custom command
     */
    'command' => [
        'php-s' => [
            'title' => 'php -S 127.0.0.1:3554',
            'code' => 'php7.2 -S 127.0.0.1:3554',
            'deps' => [],
        ],
    ],
];
