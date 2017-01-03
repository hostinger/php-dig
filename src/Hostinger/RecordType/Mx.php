<?php

namespace Hostinger\RecordType;

class Mx implements RecordType
{
    /**
     * Example of dig output
     * dig +noall +answer MX hostingermail.com
     * hostingermail.com.      81657   IN      MX      50 ASPMX3.GOOGLEMAIL.com.
     * hostingermail.com.      81657   IN      MX      40 ASPMX2.GOOGLEMAIL.com.
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
                'pri'    => $recordProp[4],
                'target' => $recordProp[5],
            ];
        }
        return $output;
    }

    public function getType()
    {
        return 'MX';
    }

}
