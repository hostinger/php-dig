<?php

namespace Hostinger;

class RecordTypeFactory
{
    private $dns_types = [
        DNS_MX    => 'MX',
        DNS_CNAME => 'CNAME',
    ];

    public function make($dns_type)
    {
        $class = '\\Hostinger\\RecordType\\' . ucfirst(strtolower($this->dns_types[$dns_type]));
        if (!class_exists($class)) {
            return null;
        }
        return new $class();
    }

}
