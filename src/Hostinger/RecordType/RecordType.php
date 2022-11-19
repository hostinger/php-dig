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
     * @return array<mixed>
     */
    public function transform(array $lines);
}
