<?php

declare(strict_types=1);

namespace Hostinger\Dig;

use ErrorException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected Command $command;

    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->command = new Command();
    }

    /**
     * @param string $domain
     * @param int $type One of the DNS_* constants
     * @param string $dnsProvider
     * @param int $timeout
     * @return array
     * @throws ErrorException
     */
    public function getRecord(string $domain, int $type, string $dnsProvider = '8.8.8.8', int $timeout = 2): array
    {
        $recordTypeFactory = new RecordTypeFactory();
        $recordType = $recordTypeFactory->make($type);
        if (is_null($recordType)) {
            $this->logger->warning('Unsupported DNS type', [
                'domain' => $domain,
                'type' => $this->dnsTypeToName($type)
            ]);
            return $this->fallback($domain, $type);
        }

        $execState = $this->execEnabled();
        if ($execState !== true) {
            $this->logger->warning('EXEC disabled', [
                'domain' => $domain,
                'type' => $recordType->getType(),
                'error' => $execState,
            ]);
            return $this->fallback($domain, $type);
        }

        $this->logger->debug('execute dig', ['domain' => $domain, 'type' => $recordType->getType()]);

        return $this->command->setTimeout($timeout)->execute($domain, $recordType, $dnsProvider);
    }

    /**
     * Use custom error handler to convert dns_get_record() errors to exceptions.
     * It throws a warning when the DNS query fails.
     * @see http://stackoverflow.com/a/1241751/2728507
     * @see http://php.net/manual/en/function.restore-error-handler.php
     * @param string $domain
     * @param int $type
     * @return array
     * @throws ErrorException
     */
    public function fallback(string $domain, int $type): array
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            return dns_get_record($domain, $type);
        } catch (ErrorException $errorException) {
            $this->logger->critical('dns_get_record() query failed', [
                'domain' => $domain,
                'type' => $this->dnsTypeToName($type),
                'error' => $errorException->getMessage(),
            ]);
        } finally {
            restore_error_handler();
        }

        return [];
    }

    /**
     * Check if system allowed to execute commands. Return true or string with error message
     * @return bool|string
     */
    public function execEnabled(): bool|string
    {
        if (!function_exists('exec')) {
            return 'missing_function_exec';
        }

        $disabled = explode(',', ini_get('disable_functions'));
        if (in_array('exec', $disabled)) {
            return 'disabled_function_exec';
        }

        $lines   = [];
        $command = 'dig -v 2>&1 | grep "not found" ';
        exec($command, $lines);
        if (!empty($lines)) {
            return 'dig not found';
        }

        return true;
    }

    private function dnsTypeToName(int $type): string
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
