<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/RedLock.php';

class RedLockTest extends TestCase
{
    private array $servers;
    private RedLock $redLock;

    protected function setUp(): void
    {
        $this->servers = [
            ['127.0.0.1', 6379, 0.01],
            ['127.0.0.1', 6389, 0.01],
            ['127.0.0.1', 6399, 0.01],
        ];
        $this->redLock = new RedLock($this->servers);
    }

    public function testLockAndUnlock(): void
    {
        $lock = $this->redLock->lock('test', 10000);
        $this->assertIsArray($lock, 'Lock should return an array');
        $this->assertArrayHasKey('token', $lock);
        $this->assertArrayHasKey('resource', $lock);
        $this->assertArrayHasKey('validity', $lock);
        $this->assertEquals('test', $lock['resource']);
        $this->assertIsString($lock['token']);
        $this->assertGreaterThan(0, $lock['validity']);

        // Unlock and check that no exception is thrown
        $this->redLock->unlock($lock);
        $this->assertTrue(true);
        $lock2 = $this->redLock->lock('test', 10000);
        $this->assertNull($lock2, 'Lock should be null because the lock is already acquired');
        
        $this->redLock->unlock($lock);
        $lock3 = $this->redLock->lock('test', 10000);
        $this->assertIsArray($lock3, 'Lock should return an array');
        $this->assertArrayHasKey('token', $lock3);
        $this->assertArrayHasKey('resource', $lock3);
        $this->assertArrayHasKey('validity', $lock3);
        $this->assertNotEquals($lock['token'], $lock3['token'], 'Tokens should be different');
    }
}
