<?php

namespace M2\Cli\Helper\Cli\IO;

use function is_resource;
use function get_resource_type;
use function stream_get_meta_data;
use function strpos;

class ResourceInputStream
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream = STDIN)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') === false && strpos($meta['mode'], '+') === false) {
            throw new \InvalidArgumentException('Expected a readable stream');
        }

        $this->stream = $stream;
    }

    public function read(int $numBytes, callable $callback) : void
    {
        $buffer = fread($this->stream, $numBytes);
        $callback($buffer);
    }

    /**
     * Whether the stream is connected to an interactive terminal
     */
    public function isInteractive() : bool
    {
        return posix_isatty($this->stream);
    }
}
