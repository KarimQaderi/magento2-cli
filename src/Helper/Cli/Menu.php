<?php

namespace M2\Cli\Helper\Cli;

use M2\Cli\Helper\Helper;

class Menu
{
    /** @var array */
    protected $list;

    /** @var UnixTerminal */
    protected $terminal;

    /** @var array */
    protected $currentItem = [];

    /** @var array */
    protected $controlMapping = [];

    /** @var Helper */
    private $helper;

    public function __construct(array $list)
    {
        $this->terminal = TerminalFactory::fromSystem();
        $this->helper = Helper::getInstance();
        $this->list = $list;
    }

    protected function addControlMapping()
    {
        $count = 0;

        foreach ($this->list as $key => &$item) {

            if (!isset($item['map'])) {
                ++$count;

                if ($count >= 10) {
                    $map = chr($count + 55);
                } else {
                    $map = $count;
                }
            } else {
                $map = $item['map'];
            }

            $item['map'] = $map;
            $item['key'] = $key;

            $this->controlMapping[strtolower($map)] = $item;
        }
    }

    protected function top()
    {
        foreach ($this->list as $item) {
            $map = $this->helper->getColorTextEcho(' [' . $item['map'] . ']', 's', false);
            echo $map . $item['title'] . "\n";
        }

        $this->helper->alert("select item:");
    }

    protected function select()
    {
        $reader = new NonCanonicalReader($this->terminal);
        $txt = '';
        $i = 0;
        $listCount = count($this->list) - 1;

        while (true) {
            $char = $reader->readCharacter();

            if ($char->isNotControl()) {
                $txt .= $char->get();
                continue;
            }

            $write = function () {
                $txt = '';

                if (isset($this->currentItem['title'])) {
                    $txt = '[●] ' . $this->currentItem['title'] . "\r";
                }

                $this->terminal->writeLine($txt);
            };

            switch ($char->getControl()) {
                case InputCharacter::UP:
                    if (0 > --$i) {
                        $i = $listCount;
                        end($this->list);
                    } else {
                        prev($this->list);
                    }
                    $this->currentItem = current($this->list);
                    $write();
                    break;

                case InputCharacter::DOWN:

                    if ($i++ >= $listCount) {
                        $i = 0;
                        reset($this->list);
                    } else {
                        ($i != 1) && next($this->list);
                    }

                    $this->currentItem = current($this->list);

                    $write();
                    break;

                case InputCharacter::ENTER:
                    $txt = strtolower($txt);

                    if (isset($this->controlMapping[$txt])) {
                        return $this->controlMapping[$txt];
                    }

                    return $this->currentItem;
            }
        }

        return '';
    }

    public function run()
    {
        $this->addControlMapping();

        $this->top();

        return $this->select();
    }
}
