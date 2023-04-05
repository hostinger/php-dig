<?php

declare(strict_types=1);

namespace Hostinger\Dig;

use Hostinger\Dig\RecordType\RecordType;

class Command
{
    public function execute(
        string $domain,
        RecordType $recordType,
        string $dnsProvider = '8.8.8.8',
        int $timeout = 2
    ): ?array {
        $dnsType = strtoupper($recordType->getType());
        $command = sprintf(
            'dig @%s +noall +answer +time=%u %s %s',
            escapeshellarg($dnsProvider),
            $timeout,
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
