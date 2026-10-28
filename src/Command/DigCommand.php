<?php

namespace Hostinger\Dig\Command;

class DigCommand
{
    public function __construct(
        private readonly string $name,
        private readonly string $type,
        private readonly ?DigOptions $options = null,
        private readonly ?DigQuery $query = null,
    ) {
    }

    public function toCliCommand(): string
    {
        $args = [];
        $args[] = escapeshellcmd('dig');

        if ($this->options) {
            $args = array_merge($args, array_values($this->options->toCliOptions()));
        }

        if ($this->query) {
            $args = array_merge($args, array_values($this->query->toCliQuery()));
        }

        // Pass type and name via -t/-q so dig never parses them as options (e.g. "-f-" or "+foo").
        $args[] = '-t ' . escapeshellarg($this->type);
        $args[] = '-q ' . escapeshellarg($this->name);

        return join(' ', $args);
    }
}
