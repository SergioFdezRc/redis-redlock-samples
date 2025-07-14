import redlock from './Redlock_config'

/**
 * This function is used to sleep for a given number of milliseconds.
 * @param milliseconds the number of milliseconds to sleep
 */
function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}

/**
 * This class is used to create a redlock factory.
 */
function RedLockFactory() {
    this.redlock = redlock;
}

/**
 * This function lock a resource with a ttl and unlock when the task is done or the ttl expires.
 * @param resource the resource to be locked
 * @param ttl the time to live of the lock
 * @returns {Bluebird<void>}
 */
RedLockFactory.prototype.lockOneResource = function lockOneResource(resource, ttl) {
    return this.redlock.lock(resource, ttl).then(lock => {

        for (let i = 0; i < 10; i++) {
            console.log("[%s] Doing task " + i, lock.value);
            sleep(500);
        }
        console.log("[%s] Unlocking resource %s", lock.value, lock.resource[0]);

        return lock.unlock().then(() => console.log("[%s] Ok", lock.value))
            .catch(err => {
                console.error(err);
            });
    });
};

/**
 * This function locks multiple resources, extends the time to live of the lock and, after that, unlock the lock.
 * @param resources the list of resources to be locked
 * @param ttl the time to live of the locks
 * @param ttl_to_extend the ttl to extends the time to live of the lock
 * @returns {Bluebird<void>}
 */
RedLockFactory.prototype.lockMultipleResources = function lockMultipleResources(resources, ttl, ttl_to_extend) {
    this.redlock.lock(resources, ttl).then(lock => {
        console.log("[%s] Resources: %s", lock.value, resources);
        for (let i = 0; i < 5; i++) {
            console.log("[%s] Doing task " + i, lock.value);
            sleep(500);
        }
        return lock.extend(ttl_to_extend).then(lock => {
            console.log("[%s] Extending ttl with %d", lock.value, ttl_to_extend);
            return lock.unlock().then(()=> console.log("[%s] Ok", lock.value))
                .catch(err => {
                    console.error(err);
                });
        });

    });
};

/**
 * This function lock a resource with a ttl, extends the time to live of the lock while doing tasks and unlock it when the
 * task is done or the ttl is expires.
 * @param resource the resource to be locked
 * @param ttl the time to live of the lock
 * @param ttl_to_extend the ttl to extends the time to live of the lock
 * @returns {Bluebird<void>}
 */
RedLockFactory.prototype.lockAndExtendResource = function lockAndExtendResource(resource, ttl, ttl_to_extend) {
    this.redlock.lock(resource, ttl).then(lock => {
        console.log("[ONE RESOURCES] Doing task...");
        return lock.extend(ttl_to_extend).then(lock => {
            console.log("[ONE RESOURCES] Extending ttl...");
            return lock.unlock()
                .catch(err => {
                    console.error(err);
                });
        });
    });
};


const redLockFactory = new RedLockFactory();
export default redLockFactory;