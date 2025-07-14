# Redis Redlock PHP Sample

A modern PHP implementation of the [Redlock algorithm](https://redis.io/topics/distlock) for distributed locks using Redis. This library allows you to safely acquire and release distributed locks across multiple Redis instances, following best practices for reliability and performance.


**Author:** Sergio Fernández Rincón <sergiofdezrc@gmail.com>
**Version:** 1.0  
**Course:** Distributed Operating Systems  
**Degree:** Master's in Computer Engineering  
**University:** University of Extremadura


## Features
- Implements the Redlock distributed locking algorithm
- Compatible with PHP 8+
- Uses the modern Redis extension
- PSR-4 autoloading
- Includes PHPUnit tests

## Requirements
- PHP 8.1 or higher
- [phpredis extension](https://github.com/phpredis/phpredis) (version 5.3+ recommended)
- At least 3 Redis servers (for quorum)
- Composer

## Installation

Clone the repository and install dependencies:

```bash
composer install
```

## Usage Example

```php
require_once __DIR__ . '/redlock-php/src/RedLock.php';

$servers = [
    ['127.0.0.1', 6379, 0.01],
    ['127.0.0.1', 6389, 0.01],
    ['127.0.0.1', 6399, 0.01],
];

$redLock = new RedLock($servers);

$lock = $redLock->lock('example_resource', 10000); // 10 seconds TTL

if ($lock) {
    echo "Lock acquired!\n";
    // TODO: Do your protected work here
    $redLock->unlock($lock);
    echo "Lock released.\n";
} else {
    echo "Could not acquire lock.\n";
}
```

See `main/redlock.php` for a complete example.

## Running Tests

To run the automated tests, ensure you have at least three Redis servers running on the default ports (6379, 6389, 6399), then execute:

```bash
vendor/bin/phpunit --bootstrap vendor/autoload.php redlock-php/tests/RedLockTest.php
```

## Project Structure

- `redlock-php/src/RedLock.php` — Main RedLock implementation
- `redlock-php/tests/RedLockTest.php` — PHPUnit test suite
- `main/redlock.php` — Example usage script
