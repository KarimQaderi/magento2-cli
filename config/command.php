<?php

/**
 * Please do not add or remove anything
 */

$magentoCode = '{firstCode} {php} "{dir}bin/magento" ';

return [
    'm1' => [
        'title' => 'upgrade all code',
        'code' => '',
        'deps' => ['m5', 'm3', 'm11', 'm4'],
    ],

    'm11' => [
        'title' => 'force deploy -f',
        'code' => implode(' && ', [
            'rm -rf "{dir}pub/static/adminhtml"',
            'rm -rf "{dir}pub/static/frontend"',
            'rm -rf "{dir}pub/static/_cache"',
            'rm -rf "{dir}var/cache"',
            'rm -rf "{dir}var/view_preprocessed"'
        ]),
        'deps' => ['m2'],
    ],

    'm2' => [
        'title' => 'deploy -f',
        'code' => $magentoCode . 'setup:static-content:deploy -f {more}',
        'ask' => [
            'more' => [
                'title' => 'More Append Data Ex: fa_IR',
                'default' => 'fa_IR en_US',
            ],
        ],
    ],

    'm3' => [
        'title' => 'compile',
        'code' => $magentoCode . 'setup:di:compile',
    ],

    'm4' => [
        'title' => 'cache:flush',
        'code' => $magentoCode . 'cache:flush',
    ],

    'm5' => [
        'title' => 'setup:upgrade',
        'code' => $magentoCode . 'setup:upgrade',
    ],

    'm6' => [
        'title' => 'developer',
        'code' => $magentoCode . 'deploy:mode:set developer',
    ],

    'm7' => [
        'title' => 'production',
        'code' => $magentoCode . 'deploy:mode:set production',
    ],

    'm8' => [
        'title' => 'reindex',
        'code' => $magentoCode . 'indexer:reset && ' . $magentoCode . 'indexer:reindex',
    ],

    'm9' => [
        'title' => 'resize',
        'code' => $magentoCode . 'catalog:image:resize',
    ],

    'm10' => [
        'title' => 'Create Admin',
        'code' => implode(' ', [
            $magentoCode,
            'admin:user:create',
            '--admin-user="{username}"',
            '--admin-password="{password}"',
            '--admin-email="{email}"',
            '--admin-firstname="{firstname}"',
            '--admin-lastname="{lastname}"',
        ]),
        'ask' => [
            'username' => [
                'title' => 'User Name',
                'default' => 'Magento',
            ],
            'password' => [
                'title' => 'Password',
                'default' => '',
            ],
            'email' => [
                'title' => 'Email',
                'default' => 'magento@magento.magento',
            ],
            'firstname' => [
                'title' => 'Firstname',
                'default' => 'Magento',
            ],
            'lastname' => [
                'title' => 'Lastname',
                'default' => 'Magento',
            ],
        ],
    ],

    'm11-debug' => [
        'title' => 'debug',
        'code' => ' echo "https://devdocs.magento.com/guides/v2.4/config-guide/cli/logging.html"',
    ],
];
