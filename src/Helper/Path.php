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
        return BS . $path;
    }

    public function stub($file, $fileGetContent = false)
    {
        $src = $this->cli('stubs/' . $file . '.stub');

        if (!$fileGetContent) {
            return $src;
        }

        return file_get_contents($src);
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
