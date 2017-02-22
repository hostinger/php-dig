<?php

namespace Hostinger\RecordType;

class Cname implements RecordType
{
    /**
     * Example of dig output
     * dig +noall +answer CNAME ghs.google.com
     * ghs.google.com.         46441   IN      CNAME   ghs.l.google.com.
     * @param array $lines
     * @return array
     */
    public function transform(array $lines)
    {
        $output = [];
        foreach ($lines as $line) {
            $recordProp = explode(' ', preg_replace('!\s+!', ' ', $line));
            if ($recordProp[3] != $this->getType()) {
                continue;
            }

            $output[] = [
                'host'   => trim($recordProp[0], '\.'),
                'ttl'    => $recordProp[1],
                'class'  => $recordProp[2],
                'type'   => $recordProp[3],
                'target' => trim($recordProp[4], '\.'),
            ];
        }
        return $output;
    }

    public function getType()
    {
        return 'CNAME';
    }

}
