<?php

namespace M2\Cli\App;

use M2\Cli\Traits\Singleton;
use M2\Cli\Helper\Helper;

/**
 * @property Helper helper
 */
class Process
{
    use Singleton;

    public function __construct()
    {
        $this->helper = Helper::getInstance();
    }

    public function run($command)
    {
        $this->helper->alert('Run: ' . $command);

        $this->runCommand($command);

        $this->helper->alert('End: ' . $command);
    }

    protected function runCommand($cmd)
    {
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), []);
        $buffer_len = $prev_buffer_len = 0;
        $ms = 10;
        $read_output = true;
        $read_error = true;
        stream_set_blocking($pipes[1], 0);
        stream_set_blocking($pipes[2], 0);

        while ($read_error != false || $read_output != false) {

            if ($read_output != false) {
                if (feof($pipes[1])) {
                    fclose($pipes[1]);
                    $read_output = false;
                } else {
                    $str = fread($pipes[1], 1024);
                    $len = strlen($str);

                    if ($len) {
                        echo $str;
                        $buffer_len += $len;
                    }
                }
            }

            if ($read_error != false) {
                if (feof($pipes[2])) {
                    fclose($pipes[2]);
                    $read_error = false;
                } else {
                    $str = fread($pipes[2], 1024);
                    $len = strlen($str);

                    if ($len) {
                        $this->helper->alert($str, 'e');
                        $buffer_len += $len;
                    }
                }
            }

            if ($buffer_len > $prev_buffer_len) {
                $prev_buffer_len = $buffer_len;
                $ms = 10;
            } else {
                usleep($ms * 1000); // sleep for $ms milliseconds
                if ($ms < 160) {
                    $ms = $ms * 2;
                }
            }
        }

        return proc_close($process);
    }
}
