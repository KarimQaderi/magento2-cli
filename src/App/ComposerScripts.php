<?php

namespace M2\Cli\App;

use Composer\Script\Event;
use M2\Cli\Helper\Helper;
use M2\Cli\App\Install;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param Event $event
     *
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        $helper = Helper::getInstance();

        return;

        $target = __DIR__.'/../m2';

        if (file_exists($target)){
            return;
        }

        $file = __DIR__ . '/../../m2';

        symlink($target, $file);

        $helper->alert('stubs m2 add');

        $helper->alertExit('success install m2', 's');
    }
}
