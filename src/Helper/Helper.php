<?php

namespace M2\Cli\Helper;

use M2\Cli\Traits\Singleton;

class Helper
{
    use Singleton;

    public function getColorTextEcho($str, $type = 'i', $newLine = true)
    {
        switch ($type) {
            case 'e': //error
                $str = "\033[31m$str \033[0m";
                break;

            case 's': //success
                $str = "\033[32m$str \033[0m";
                break;

            case 'w': //warning
                $str = "\033[33m$str \033[0m";
                break;

            case 'i': //info
                $str = "\033[36m$str \033[0m";
                break;
        }

        if (!$newLine) {
            return $str;
        }

        return $str . "\n";
    }

    public function alert($str, $type = 'i')
    {
        echo $this->getColorTextEcho($str, $type);
    }

    public function alertExit($str, $type = 'i')
    {
        $this->alert($str, $type);

        exit(1);
    }

    public function config()
    {
        return Config::getInstance();
    }

    public function path()
    {
        return Path::getInstance();
    }

    public function logs()
    {
        return Log::getInstance();
    }
}
