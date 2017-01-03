<?php

namespace Hostinger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class DigClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function getRecord($domain, $type)
    {
        $recordTypeFactory = new RecordTypeFactory();
        $recordType        = $recordTypeFactory->make($type);
        if (is_null($recordType)) {
            $this->logger->warning('Unsupported DNS type',
                ['domain' => $domain, 'type' => $recordTypeFactory->convertDnsTypeToString($type)]);
            return $this->fallback($domain, $type);
        }

        if ($errorCode = $this->execEnabled() !== true) {
            $this->logger->warning('EXEC disabled',
                ['domain' => $domain, 'type' => $recordType->getType(), 'error' => $errorCode]);
            return $this->fallback($domain, $type);
        }

        $this->logger->debug('execute dig', ['domain' => $domain, 'type' => $recordType->getType()]);
        return (new ExecuteDigCommand())->execute($domain, $recordType);
    }

    /**
     * Use custom error handler to convert dns_get_record() errors to exceptions.
     * It throws a warning when the DNS query fails.
     * @see http://stackoverflow.com/a/1241751/2728507
     * @see http://php.net/manual/en/function.restore-error-handler.php
     * @param $domain
     * @param $type
     * @return array
     */
    public function fallback($domain, $type)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }

            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        $output = [];
        try {
            $output = dns_get_record($domain, $type);
        } catch (\ErrorException $errorException) {
            $this->logger->critical('dns_get_record() query failed',
                ['domain' => $domain, 'type' => $type, 'error' => $errorException->getMessage()]);
        }

        restore_error_handler();
        return $output;
    }

    /**
     * Check if system allowed to execute commands. Return true or string with error message
     * @return bool|string
     */
    public function execEnabled()
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
}
