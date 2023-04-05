<?php

declare(strict_types=1);

namespace Hostinger\Dig;

use Hostinger\Dig\RecordType\A;
use Hostinger\Dig\RecordType\AAAA;
use Hostinger\Dig\RecordType\Cname;
use Hostinger\Dig\RecordType\Mx;
use Hostinger\Dig\RecordType\Ns;
use Hostinger\Dig\RecordType\RecordType;

class RecordTypeFactory
{
    /**
     * @param int $dnsType One of the DNS_* constants
     * @return RecordType|null
     */
    public function make(int $dnsType): ?RecordType
    {
        return match ($dnsType) {
            DNS_A => new A(),
            DNS_AAAA => new AAAA(),
            DNS_MX => new Mx(),
            DNS_CNAME => new Cname(),
            DNS_NS => new Ns(),
            default => null,
        };
    }

    static public function dnsTypeToName(int $type): string
    {
        return match ($type) {
            DNS_A => 'A',
            DNS_CAA => 'CAA',
            DNS_NS => 'NS',
            DNS_CNAME => 'CNAME',
            DNS_SOA => 'SOA',
            DNS_PTR => 'PTR',
            DNS_HINFO => 'HINFO',
            DNS_MX => 'MX',
            DNS_TXT => 'TXT',
            DNS_SRV => 'SRV',
            DNS_NAPTR => 'NAPTR',
            DNS_AAAA => 'AAAA',
            DNS_A6 => 'A6',
            DNS_ANY => 'ANY',
            DNS_ALL => 'ALL',
            default => 'UNKNOWN',
        };
    }
}
