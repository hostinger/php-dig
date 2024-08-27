<?php

namespace Hostinger\Dig\Command;

class DigQuery
{
    public function __construct(
        public readonly ?bool $all = null,
        public readonly ?bool $answer = null,
        public readonly ?bool $authority = null,
        public readonly ?int $time = null,
    ) {
    }

    public function toCliQuery(): array
    {
        $opts = [];

        if ($this->all !== null) {
            $opts['all'] = escapeshellarg('+' . ($this->all ? 'all' : 'noall'));
        }

        if ($this->answer !== null) {
            $opts['answer'] = escapeshellarg('+' . ($this->answer ? 'answer' : 'noanswer'));
        }

        if ($this->authority !== null) {
            $opts['authority'] = escapeshellarg('+' . ($this->authority ? 'authority' : 'noauthority'));
        }

        if ($this->time !== null) {
            $opts['time'] = escapeshellarg('+time=' . $this->time);
        }

        return $opts;
    }
}
