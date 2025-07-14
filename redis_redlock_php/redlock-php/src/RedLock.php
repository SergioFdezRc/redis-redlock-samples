<?php

declare(strict_types=1);

/**
 * Class RedLock
 * Implements the Redlock algorithm for distributed locks using Redis.
 * Compatible with PHP 8+ and modern Redis extension.
 */
class RedLock
{
    /** @var int Delay between retries in milliseconds */
    private int $retryDelay;

    /** @var int Number of retry attempts */
    private int $retryCount;

    /** @var float Clock drift factor for validity calculation */
    private float $clockDriftFactor = 0.01;

    /** @var int Number of Redis instances required for quorum */
    private int $quorum;

    /** @var array List of Redis server configurations */
    private array $servers = [];
    
    /** @var Redis[] List of Redis client instances */
    private array $instances = [];

    /**
     * RedLock constructor.
     * @param array $servers Array of arrays: [host, port, timeout]
     * @param int $retryDelay Delay between retries in milliseconds
     * @param int $retryCount Number of retry attempts
     */
    public function __construct(array $servers, int $retryDelay = 200, int $retryCount = 3)
    {
        $this->servers = $servers;
        $this->retryDelay = $retryDelay;
        $this->retryCount = $retryCount;
        $this->quorum = min(count($servers), (int) (count($servers) / 2 + 1));
    }

    /**
     * Attempts to acquire a distributed lock for a resource.
     * @param string $resource The resource to lock
     * @param int $ttl Time to live in milliseconds
     * @return array|false Lock information if successful, false otherwise
     */
    public function lock(string $resource, int $ttl): array|false
    {
        $this->initInstances();
        $token = bin2hex(random_bytes(16));
        $retry = $this->retryCount;

        do {
            $n = 0;
            $startTime = microtime(true) * 1000;

            foreach ($this->instances as $instance) {
                if ($this->lockInstance($instance, $resource, $token, $ttl)) {
                    $n++;
                }
            }

            // Add 2 milliseconds to the drift to account for Redis expires precision
            $drift = ($ttl * $this->clockDriftFactor) + 2;
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;

            if ($n >= $this->quorum && $validityTime > 0) {
                return [
                    'validity' => (int) $validityTime,
                    'resource' => $resource,
                    'token'    => $token,
                ];
            } else {
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance, $resource, $token);
                }
            }

            $delay = random_int((int) floor($this->retryDelay / 2), $this->retryDelay);
            usleep($delay * 1000);
            $retry--;
        } while ($retry > 0);

        return false;
    }

    /**
     * Releases a previously acquired lock.
     * @param array $lock The lock array returned by lock()
     * @return void
     */
    public function unlock(array $lock): void
    {
        $this->initInstances();
        $resource = $lock['resource'] ?? null;
        $token    = $lock['token'] ?? null;
        if (!$resource || !$token) {
            return;
        }
        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance, $resource, $token);
        }
    }

    /**
     * Initializes Redis client instances for all configured servers.
     * @return void
     */
    private function initInstances(): void
    {
        if (empty($this->instances)) {
            foreach ($this->servers as $server) {
                [$host, $port, $timeout] = $server;
                $redis = new Redis();
                $redis->connect($host, $port, $timeout);
                $this->instances[] = $redis;
            }
        }
    }

    /**
     * Attempts to acquire a lock on a single Redis instance.
     * @param Redis $instance The Redis client
     * @param string $resource The resource to lock
     * @param string $token The unique lock token
     * @param int $ttl Time to live in milliseconds
     * @return bool True if lock acquired, false otherwise
     */
    private function lockInstance(Redis $instance, string $resource, string $token, int $ttl): bool
    {
        return $instance->set($resource, $token, ['NX', 'PX' => $ttl]) === true;
    }

    /**
     * Releases a lock on a single Redis instance if the token matches.
     * @param Redis $instance The Redis client
     * @param string $resource The resource to unlock
     * @param string $token The unique lock token
     * @return int 1 if the lock was released, 0 otherwise
     */
    private function unlockInstance(Redis $instance, string $resource, string $token): int
    {
        $script = <<<'LUA'
            if redis.call("GET", KEYS[1]) == ARGV[1] then
                return redis.call("DEL", KEYS[1])
            else
                return 0
            end
        LUA;
        return (int) $instance->eval($script, [$resource, $token], 1);
    }
}
