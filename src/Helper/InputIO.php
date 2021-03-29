<?php

namespace M2\Cli\Helper;

use M2\Cli\Traits\Singleton;
use M2\Cli\Helper\Cli\Menu;
use M2\Cli\Helper\Cli\TerminalFactory;
use M2\Cli\Helper\Cli\NonCanonicalReader;
use M2\Cli\Helper\Cli\InputCharacter;

/**
 * @property Helper helper
 */
class InputIO
{
    use Singleton;

    protected $terminal;

    public function __construct()
    {
        $this->terminal = TerminalFactory::fromSystem();
        $this->helper = Helper::getInstance();
    }

    public function yesNo($ask)
    {
        echo $this->helper->getColorTextEcho($ask, 'w', false) . ' ';
        echo $this->helper->getColorTextEcho('[Y,n]', 's');

        $reader = new NonCanonicalReader($this->terminal);

        while ($char = $reader->readCharacter()) {
            if ($char->isNotControl()) {
                return $char->get();
            }

            if ($char->isHandledControl()) {
                switch ($char->getControl()) {
                    case InputCharacter::ENTER:
                        return 'y';
                }
            }
        }

        return '';
    }

    public function menu(array $list)
    {
        $select = (new Menu($list))->run();

        return $select;
    }

    public function ask($ask, $default = '')
    {
        echo $this->helper->getColorTextEcho($ask, 's', empty($default));

        if (!empty($default)) {
            echo $this->helper->getColorTextEcho('[ default: ', 'i', false);
            echo $this->helper->getColorTextEcho($default, 'w', false);
            echo $this->helper->getColorTextEcho(']', 'i');
        }

        $reader = new NonCanonicalReader($this->terminal);

        $inputValue = '';

        while ($char = $reader->readCharacter()) {
            if ($char->isNotControl()) {
                $inputValue .= $char->get();
                $this->terminal->writeLine($inputValue);
                continue;
            }

            if ($char->isHandledControl()) {
                switch ($char->getControl()) {
                    case InputCharacter::ESC:
                    case InputCharacter::ENTER:
                        if (empty($inputValue)) {
                            $this->terminal->writeNewLine($default);
                            return $default;
                        }
                        return $inputValue;

                    case InputCharacter::BACKSPACE:
                        $inputValue = substr($inputValue, 0, -1);
                        $this->terminal->writeLine($inputValue);
                        continue 2;
                }
            }
        }

        return '';
    }
}
