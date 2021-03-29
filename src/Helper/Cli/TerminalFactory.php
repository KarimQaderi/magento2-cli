<?php

namespace M2\Cli\Helper\Cli;

use M2\Cli\Helper\Cli\IO\ResourceInputStream;
use M2\Cli\Helper\Cli\IO\ResourceOutputStream;

class TerminalFactory
{
    public static function fromSystem()
    {
        return new UnixTerminal(new ResourceInputStream(), new ResourceOutputStream());
    }
}
