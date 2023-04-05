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
}
