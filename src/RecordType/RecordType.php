<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

interface RecordType
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param array $lines
     * @return array
     */
    public function transform(array $lines): array;
}
