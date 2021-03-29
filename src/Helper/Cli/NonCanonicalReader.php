<?php

namespace M2\Cli\Helper\Cli;

class NonCanonicalReader
{
    /**
     * @var UnixTerminal
     */
    private $terminal;

    /**
     * @var bool
     */
    private $wasCanonicalModeEnabled;

    /**
     * Map of characters to controls.
     * Eg map 'w' to the up control.
     *
     * @var array
     */
    private $mappings = [];

    public function __construct(UnixTerminal $terminal)
    {
        $this->terminal = $terminal;
        $this->wasCanonicalModeEnabled = $terminal->isCanonicalMode();
        $this->terminal->disableCanonicalMode();
    }

    public function addControlMapping(string $character, string $mapToControl) : void
    {
        if (!InputCharacter::controlExists($mapToControl)) {
            throw new \InvalidArgumentException(sprintf('Control "%s" does not exist', $mapToControl));
        }

        $this->mappings[$character] = $mapToControl;
    }

    public function addControlMappings(array $mappings) : void
    {
        foreach ($mappings as $character => $mapToControl) {
            $this->addControlMapping($character, $mapToControl);
        }
    }

    /**
     * This should be ran with the terminal canonical mode disabled.
     *
     * @return InputCharacter
     */
    public function readCharacter() : InputCharacter
    {
        $char = $this->terminal->read(4);

        if (isset($this->mappings[$char])) {
            return InputCharacter::fromControlName($this->mappings[$char]);
        }

        return new InputCharacter($char);
    }

    public function __destruct()
    {
        if ($this->wasCanonicalModeEnabled) {
            $this->terminal->enableCanonicalMode();
        }
    }
}
