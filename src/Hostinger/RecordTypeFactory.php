<?php

namespace Hostinger;

class RecordTypeFactory
{
    private $dnsTypes = [
        DNS_MX    => 'MX',
        DNS_CNAME => 'CNAME',
        DNS_NS    => 'NS',
    ];

    public function make($dns_type)
    {
        $class = '\\Hostinger\\RecordType\\' . ucfirst(strtolower($this->dnsTypes[$dns_type]));
        if (!class_exists($class)) {
            return null;
        }
        return new $class();
    }

}
