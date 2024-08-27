<?php

namespace Hostinger\Dig\Command;

class DigOptions
{
    public function __construct(
        public readonly ?string $server = null,
        public readonly ?string $name = null,
        public readonly ?string $type = null,
    ) {
    }

    public function toCliOptions(): array
    {
        $opts = [];

        if ($this->server !== null) {
            $opts['server'] = escapeshellarg('@' . $this->server);
        }

        if ($this->name !== null) {
            $opts['name'] = '-q ' . escapeshellarg($this->name);
        }

        if ($this->type !== null) {
            $opts['type'] = '-t ' . escapeshellarg($this->type);
        }

        return $opts;
    }
}
