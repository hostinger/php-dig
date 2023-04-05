<?php

declare(strict_types=1);

namespace Hostinger\Dig;

use Hostinger\Dig\RecordType\RecordType;

class Command
{
    private int $timeout = 2;

    public function setTimeout(int $value): Command
    {
        $this->timeout = $value;

        return $this;
    }

    public function execute(string $domain, RecordType $recordType, string $dnsProvider = '8.8.8.8'): ?array
    {
        $dnsType = strtoupper($recordType->getType());
        $command = sprintf(
            'dig @%s +noall +answer +time=%s %s %s',
            escapeshellarg($dnsProvider),
            escapeshellarg((string) $this->timeout),
            escapeshellarg($dnsType),
            escapeshellarg($domain)
        );

        exec($command, $output, $returnCode);
        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        return $recordType->transform($output);
    }
}
