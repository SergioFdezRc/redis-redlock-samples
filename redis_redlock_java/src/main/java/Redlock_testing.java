import org.redisson.Redisson;
import org.redisson.api.RMap;
import org.redisson.api.RedissonClient;
import org.redisson.api.RLock;

import java.util.Collection;
import java.util.HashSet;
import java.util.Map;
import java.util.Set;
import java.util.concurrent.TimeUnit;

/**
 * Example class demonstrating Redis connection and Redlock usage with Redisson.
 * <p>
 * This class connects to a Redis server, performs basic map operations, and demonstrates
 * distributed locking using the Redlock algorithm via Redisson's RLock interface.
 * </p>
 *
 * @author TuNombre
 */
public class RedlockTesting {
    /**
     * Entry point for the application.
     *
     * @param args command line arguments (not used)
     */
    public static void main(String[] args) {
        RedlockTesting app = new RedlockTesting();
        app.run();
    }

    /**
     * Redisson client instance for Redis operations.
     */
    private RedissonClient redisson;

    /**
     * Orchestrates the connection, map operations, and Redlock demonstration.
     */
    public void run() {
        try {
            connect();
            RMap<String, Integer> map = createAndPopulateMap();
            printMapInfo(map);
            testFastPutMethods(map);
            testRedlock();
        } catch (Exception e) {
            System.err.println("General error: " + e.getMessage());
            e.printStackTrace();
        } finally {
            disconnect();
        }
    }

    /**
     * Establishes a connection to the Redis server using Redisson.
     */
    private void connect() {
        redisson = Redisson.create();
        System.out.println("Connected to Redis.");
    }

    /**
     * Shuts down the Redisson client and releases resources.
     */
    private void disconnect() {
        if (redisson != null) {
            redisson.shutdown();
            System.out.println("Redisson shut down successfully.");
        }
    }

    /**
     * Creates and populates a Redis map with sample data.
     *
     * @return the populated RMap instance
     */
    private RMap<String, Integer> createAndPopulateMap() {
        RMap<String, Integer> map = redisson.getMap("myMap");
        map.put("a", 1);
        map.put("b", 2);
        map.put("c", 3);
        return map;
    }

    /**
     * Prints information and statistics about the provided Redis map.
     *
     * @param map the RMap instance to inspect
     */
    private void printMapInfo(RMap<String, Integer> map) {
        if (map.containsKey("a")) {
            System.out.println("Key 'a' exists in the map.");
        }
        System.out.println("Value of 'c': " + map.get("c"));
        System.out.println("Value of 'a' before update: " + map.get("a"));
        System.out.println("Value of 'a' after adding 32: " + map.addAndGet("a", 32));
        System.out.println("Size of value 'c': " + map.valueSize("c"));

        Set<String> keys = new HashSet<>();
        keys.add("a");
        keys.add("b");
        keys.add("c");
        Map<String, Integer> mapSlice = map.getAll(keys);
        System.out.println("Map slice: " + mapSlice);

        Set<String> allKeys = map.readAllKeySet();
        Collection<Integer> allValues = map.readAllValues();
        Set<Map.Entry<String, Integer>> allEntries = map.readAllEntrySet();
        System.out.println("All keys: " + allKeys);
        System.out.println("All values: " + allValues);
        System.out.println("All entries: " + allEntries);
    }

    /**
     * Demonstrates fastPut and fastPutIfAbsent operations on the Redis map.
     *
     * @param map the RMap instance to operate on
     */
    private void testFastPutMethods(RMap<String, Integer> map) {
        System.out.println("Trying to put an existing key without expiration");
        boolean isNewKey = map.fastPut("a", 100);
        System.out.println(isNewKey ? "Key was added." : "Key could not be added.");

        System.out.println("Trying to put a key only if it does not exist");
        boolean isNewKeyPut = map.fastPutIfAbsent("d", 33);
        System.out.println(isNewKeyPut ? "Key 'd' was added." : "Key 'd' already existed.");

        long removedAmount = map.fastRemove("b");
        System.out.println("Number of keys removed: " + removedAmount);
    }

    /**
     * Demonstrates distributed locking using the Redlock algorithm with Redisson's RLock.
     */
    private void testRedlock() {
        RLock lock = redisson.getLock("myLock");
        boolean locked = false;
        try {
            locked = lock.tryLock(100, 1000, TimeUnit.MILLISECONDS);
            if (locked) {
                System.out.println("Lock acquired, executing critical section...");
                // Simulate work in the critical section
                Thread.sleep(500);
            } else {
                System.out.println("Could not acquire the lock.");
            }
        } catch (InterruptedException e) {
            System.err.println("Error while trying to acquire the lock: " + e.getMessage());
            Thread.currentThread().interrupt();
        } finally {
            if (locked) {
                lock.unlock();
                System.out.println("Lock released.");
            }
        }
    }
}