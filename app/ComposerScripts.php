<?php

namespace M2\Cli\App;

use Composer\Script\Event;
use M2\CliCore\App\Helper\Helper;
use M2\CliCore\App\Install;

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

        $target = __DIR__.'/../m2';

        $file = __DIR__ . '/../../m2';

        link($target, $file);

        $helper->alert('stubs m2 add');

        $helper->alertExit('success install m2', 's');
    }
}
