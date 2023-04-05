<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

/**
 * Example of dig output
 * dig +noall +answer MX hostingermail.com
 * hostingermail.com.      81657   IN      MX      50 ASPMX3.GOOGLEMAIL.com.
 * hostingermail.com.      81657   IN      MX      40 ASPMX2.GOOGLEMAIL.com.
 */
class Mx extends AbstractRecordType
{
    protected function lineColumnsToRecord(array $lineColumns): array
    {
        return [
            'host' => trim($lineColumns[0], '\.'),
            'ttl' => (int) $lineColumns[1],
            'class' => $lineColumns[2],
            'type' => $lineColumns[3],
            'pri' => $lineColumns[4],
            'target' => trim($lineColumns[5], '\.'),
        ];
    }

    public function getType(): string
    {
        return 'MX';
    }

}
