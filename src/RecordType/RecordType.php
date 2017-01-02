<?php

namespace Hostinger\RecordType;


interface RecordType
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param array $lines
     * @return array
     */
    public function transform(array $lines);
}
