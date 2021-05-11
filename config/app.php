<?php

return [
    'askDefault' => [
        'php' => 'php', // Ex: php7.2  | /usr/bin/php7.2
        'dir' => getcwd() . '/', // dir base magento
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
