<?php

namespace Hostinger\RecordType;

class Ns implements RecordType
{
    /**
     * Example of dig output
     * dig +noall +answer NS hostingermail.com
     * hostingermail.com.      3599    IN      NS      ns56.domaincontrol.com.
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
                'target' => $recordProp[4],
            ];
        }
        return $output;
    }

    public function getType()
    {
        return 'NS';
    }

}
