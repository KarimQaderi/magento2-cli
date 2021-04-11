<?php

return [
    'askDefault' => [
        'php' => 'php',
        'dir' => getcwd() . '/',
        'firstCode' => '',
    ],

    /**
     * your custom command
     */
    'command' => [
        'php-s' => [
            'title' => 'php -S 127.0.0.1:3554',
            'code' => 'php -S 127.0.0.1:3554 phpserver/router.php',
            'deps' => [],
        ],
    ],
];
