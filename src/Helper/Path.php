<?php

namespace M2\Cli\Helper;

use M2\Cli\Traits\Singleton;

class Path
{
    use Singleton;

    public function dir($path = '')
    {
        return getcwd() . '/' . $path;
    }

    public function cli($path = '')
    {
        return BS . '/' . $path;
    }

    protected function log($path)
    {
        return $this->cli('log/' . $path . '.txt');
    }

    public function logCommand()
    {
        return $this->log('command');
    }

    public function magento($path = '')
    {
        return Config::getInstance()->askDefault('dir') . '/' . $path;
    }
}
