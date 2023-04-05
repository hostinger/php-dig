<?php

declare(strict_types=1);

namespace Hostinger\Dig\RecordType;

abstract class AbstractRecordType implements RecordType
{
    public function transform(array $lines): array
    {
        $output = [];
        foreach ($lines as $line) {
            $columns = explode(' ', preg_replace('!\s+!', ' ', $line));
            if ($columns[3] != $this->getType()) {
                continue;
            }

            $output[] = $this->lineColumnsToRecord($columns);
        }

        return $output;
    }

    protected abstract function lineColumnsToRecord(array $lineColumns): array;
}
