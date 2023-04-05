<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

/**
 * Example of dig output
 * dig +noall +answer NS hostingermail.com
 * hostingermail.com.      3599    IN      NS      ns56.domaincontrol.com.
 */
class Ns extends AbstractRecordType
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
        return 'NS';
    }
}
