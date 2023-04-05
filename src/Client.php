<?php

declare(strict_types=1);

namespace Hostinger\Dig;

use Closure;
use ErrorException;
use Hostinger\Dig\RecordType\RecordType;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected Closure|null $fallback = null;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * Set custom fallback function. To reset it, pass null.
     * @param Closure|null $fallback
     */
    public function setFallback(?Closure $fallback): void
    {
        $this->fallback = $fallback;
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
                'type' => RecordTypeFactory::dnsTypeToName($type),
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

        return $this->executeDig($domain, $recordType, $dnsProvider, $timeout);
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
    protected function fallback(string $domain, int $type): array
    {
        if ($this->fallback !== null) {
            return ($this->fallback)($domain, $type);
        }

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
                'type' => RecordTypeFactory::dnsTypeToName($type),
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
    protected function execEnabled(): bool|string
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

    protected function executeDig(
        string $domain,
        RecordType $recordType,
        string $dnsProvider = '8.8.8.8',
        int $timeout = 2
    ): ?array {
        $dnsType = strtoupper($recordType->getType());
        $command = sprintf(
            'dig @%s +noall +answer +time=%u %s %s',
            escapeshellarg($dnsProvider),
            $timeout,
            escapeshellarg($dnsType),
            escapeshellarg($domain)
        );

        exec($command, $output, $returnCode);
        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        return $recordType->transform($output);
    }
}
