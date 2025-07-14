<?php

declare(strict_types=1);

require_once __DIR__ . '/../redlock-php/src/RedLock.php';

$servers = [
    ['127.0.0.1', 6379, 0.01],
    ['127.0.0.1', 6389, 0.01],
    ['127.0.0.1', 6399, 0.01],
];

$redLock = new RedLock($servers);

$lock = $redLock->lock('example_resource', 10000);

if ($lock) {
    echo "Lock acquired!\n";
    print_r($lock);
    // TODO: Do your protected work here

    // TODO: Release the lock when done
    $redLock->unlock($lock);
    echo "Lock released.\n";
} else {
    echo "Could not acquire lock.\n";
}