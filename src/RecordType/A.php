<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

/**
 * Example of dig output
 * dig +noall +answer A ghs.google.com
 * ghs.google.com.        76    IN    A    142.251.39.115
 */
class A extends AbstractRecordType
{
    public function getType(): string
    {
        return 'A';
    }

    protected function lineColumnsToRecord(array $lineColumns): array
    {
        return [
            'host' => trim($lineColumns[0], '\.'),
            'ttl' => (int) $lineColumns[1],
            'class' => $lineColumns[2],
            'type' => $lineColumns[3],
            'ip' => $lineColumns[4],
        ];
    }
}
