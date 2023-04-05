<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

/**
 * Example of dig output
 * dig +noall +answer AAAA google.com
 * google.com.        36    IN    AAAA    2a00:1450:400e:802::200e
 */
class AAAA extends AbstractRecordType
{
    public function getType(): string
    {
        return 'AAAA';
    }

    protected function lineColumnsToRecord(array $lineColumns): array
    {
        return [
            'host' => trim($lineColumns[0], '\.'),
            'ttl' => (int) $lineColumns[1],
            'class' => $lineColumns[2],
            'type' => $lineColumns[3],
            'ipv6' => $lineColumns[4],
        ];
    }
}
