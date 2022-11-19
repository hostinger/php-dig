<?php

namespace Hostinger;

use Hostinger\RecordType\RecordType;

class ExecuteDigCommand
{
    private $timeout = 2;

    public function setTimeout($value)
    {
        $this->timeout = $value;
    }

    /**
     *
     * @param string $domain
     * @param RecordType $recordType
     * @param string $serverIp
     * @return array<mixed>
     */
    public function execute(
        string $domain,
        RecordType $recordType,
        string $serverIp = '8.8.8.8'
    ): array {
        $lines   = [];
        $dnsType = strtoupper($recordType->getType());
        $command = 'dig @'.$serverIp.' +noall +answer +time=' . escapeshellarg($this->timeout) . ' ' . escapeshellarg($dnsType) . ' ' . escapeshellarg($domain);
        exec($command, $lines);

        if (empty($lines)) {
            return [];
        }

        return $recordType->transform($lines);
    }
}
