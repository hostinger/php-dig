<?php

namespace Hostinger;

use Hostinger\RecordType\RecordType;

class RecordTypeFactory
{
    private $dnsTypes = [
        DNS_MX    => 'MX',
        DNS_CNAME => 'CNAME',
        DNS_NS    => 'NS',
    ];

    /**
     * @param int $dnsType
     * @return RecordType
     */
    public function make($dnsType)
    {
        $class = '\\Hostinger\\RecordType\\' . ucfirst(strtolower($this->convertDnsTypeToString($dnsType)));
        if (!class_exists($class)) {
            return null;
        }
        return new $class();
    }

    /**
     * @param int $dnsType
     * @return string
     */
    public function convertDnsTypeToString($dnsType)
    {
        return $this->dnsTypes[$dnsType];
    }

}
