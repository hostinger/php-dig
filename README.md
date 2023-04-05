# php-dig
## Introduction

[PHP DNS functions](http://php.net/manual/en/ref.network.php) don't have a timeout while the default timeout for dig is 5 seconds (with several (3) tries) 

It should drastically decrease time to get dns records, and lower failure errors like `dns_get_record(): A temporary server error occurred.`

## Installation

For now this package is not on [Packgist](https://packagist.org/), so you need to add it to your composer.json manually
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:hostinger/php-dig.git"
        }
    ]
}
```

Install the latest version with
```console
$ composer require hostinger/php-dig
```

## Usage

```php
$client = new \Hostinger\Dig\Client();
$result = $client->getRecord('hostinger.com', DNS_MX);
```

This is equal to 
```php
dns_get_record('hostinger.com', DNS_MX);
```

Package checks if it can run `exec` in server environment, otherwise it will fallback to dns_get_record().

### DigClient implements LoggerAwareInterface
You can set [logger](https://github.com/Seldaek/monolog/) to debug / log package activity

```php
$client = new \Hostinger\Dig\Client();
$logger = new \Monolog\Logger\Logger('App');
$logger->pushHandler(new StreamHandler('path/to/your.log'));
$client->setLogger($logger);
```

## About

### Requirements

- php-dig client works with PHP 8.0 or above.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/hostinger/php-dig/issues)


## Sources
- [Stack overflow question What would cause checkdnsrr() or dns_get_record() to take too long?](http://stackoverflow.com/questions/14065946/what-would-cause-checkdnsrr-or-dns-get-record-to-take-too-long)
- [Reddit thread: dns_get_record suddenly running very slowly](https://www.reddit.com/r/PHP/comments/2k3ns7/dns_get_record_suddenly_running_very_slowly/)
