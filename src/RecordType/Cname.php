<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

/**
 * Example of dig output
 * dig +noall +answer CNAME ghs.google.com
 * ghs.google.com.         46441   IN      CNAME   ghs.l.google.com.
 */
class Cname extends AbstractRecordType
{
    protected function lineColumnsToRecord(array $lineColumns): array
    {
        return [
            'host' => trim($lineColumns[0], '\.'),
            'ttl' => (int) $lineColumns[1],
            'class' => $lineColumns[2],
            'type' => $lineColumns[3],
            'target' => trim($lineColumns[4], '\.'),
        ];
    }

    public function getType(): string
    {
        return 'CNAME';
    }

}
