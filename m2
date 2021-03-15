#!/usr/bin/env php
<?php

use M2\CliCore\App\Index;

define('BS', __DIR__);

require __DIR__ . '/vendor/autoload.php';

(new Index)->run();
