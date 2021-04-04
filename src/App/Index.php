<?php

namespace M2\Cli\App;

use M2\Cli\Helper\InputIO;
use M2\Cli\Helper\Helper;

/**
 * @property Helper  helper
 * @property InputIO inputIO
 * @property array   commandAll
 */
class Index
{

    public function __construct()
    {
        $this->helper = Helper::getInstance();
        $this->inputIO = InputIO::getInstance();
        $this->commandAll = $this->helper->config()->commandAll();
        $this->check();
    }

    protected function check()
    {
        if (PHP_SAPI !== 'cli') {
            $this->helper->alertExit('must be run as a CLI application');
        }

        $composerMagento = @file_get_contents(
            $this->helper->path()->magento('composer.json')
        );

        if (strpos($composerMagento, 'Magento') === false) {
            $this->helper->alertExit('megento not found', 'e');
        }
    }

    public function run()
    {
        $list = $this->commandAll;

        $item = $this->inputIO->menu($list);

        if (!isset($item['key'])) {
            $this->helper->alertExit('command not found');
        }

        $build = $this->buildCommand($item['key']);

        if (!is_array($build)) {
            $this->helper->alertExit('There was a problem');
        }

        $magentoDir = $this->helper->config()->askDefault('dir');

        $this->helper->alert('magento: ' . $magentoDir, 'w');

        $buildText = implode("\n", array_map(function ($build) {
            return trim($build);
        }, $build));

        $this->helper->alert(
            str_replace($magentoDir, '', $buildText)
        );

        if ($item['ask-run']) {
            $ask = $this->inputIO->yesNo('Do you want to run this command?');

            if ($ask != 'y') {
                $this->helper->alertExit('command exit');
            }
        }

        if (isset($item['callback'])) {
            $item['callback']();
        }

        foreach ($build as $comm) {
            Process::getInstance()->run($comm);
        }

        $this->helper->alertExit('OK');
    }

    protected function buildCommand($code, $isDep = false)
    {
        $commands = $this->commandAll;

        if (empty($code) || !isset($commands[$code])) {
            $this->helper->alertExit('command not found');
        }

        $command = $commands[$code];

        $codes = [];

        if (isset($command['deps']) && is_array($command['deps'])) {
            foreach ($command['deps'] as $depCode) {
                $re = $this->buildCommand($depCode, true);

                if (is_array($re)) {
                    $codes = array_merge($codes, $re);
                } else {
                    $codes[] = $this->buildCommand($depCode, true);
                }
            }
        }

        $askValues = $this->helper->config()->askDefault();

        if (isset($command['ask']) && is_array($command['ask'])) {
            $askValues = $this->getAsk($command['ask'], $askValues);
        }

        $code = '';
        if (isset($command['code'])) {
            $code = $command['code'];

            foreach ($askValues as $key => $val) {
                $code = str_replace('{' . $key . '}', $val, $code);
            }

            array_unshift($codes, $code);

            return $codes;
        }

        if ($isDep) {
            return '';
        }

        if (!empty($code)) {
            array_unshift($codes, $code);
        }

        return $codes;
    }

    /**
     * @param array $asks
     * @param array $askValues
     *
     * @return array
     */
    protected function getAsk(array $asks, array $askValues)
    {
        foreach ($asks as $key => $ask) {
            $askValues[$key] = $this->inputIO->ask($ask['title'], $ask['default']);
        }

        return $askValues;
    }
}
