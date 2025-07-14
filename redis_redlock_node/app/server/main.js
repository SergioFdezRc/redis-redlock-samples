import redLockFactory from "../../RedLockFactory";
import express from 'express'

const server = express();


const resource = 'locks:Room:1111';
const resources = ['locks:Station:1110', 'locks:Train:1101', 'locks:Car:1011'];
let ttl = 10000;
let ttl_to_extend = 1000;


server.get('/', (req, res) => {
    res.send("Hello World from Redis RedLock!!!")
});

/**
 * This function locks a resource.
 * @param req the request object
 * @param res the response object
 */
server.get('/lockOneResource', (req, res) => {
    console.log("Trying to lock the resource " + resource + " with a ttl of " + ttl);
    for (let i = 0; i < 3; i++) {
        let lock = redLockFactory.lockOneResource(resource, ttl);
        console.log("Status %d", i);
        console.log(lock);
    }

    res.send("Trying to lock the resource " + resource + " with a ttl of " + ttl);

});


/**
 * This function locks a resource and extends the time to live of the lock.
 * @param req the request object
 * @param res the response object
 */
server.get('/lockAndExtendResource', (req, res) => {
    res.send("Trying to lock the resource " + resource + " with a ttl of " + ttl + " and extends it to " + ttl_to_extend);
    res.send(redLockFactory.lockAndExtendResource(resource, ttl, ttl_to_extend));
    res.send("Done");
});

/**
 * This function locks multiple resources.
 * @param req the request object
 * @param res the response object
 */
server.get('/lockMultipleResources', (req, res) => {
    console.log("Trying to lock the resources " + resources + " with a ttl of " + ttl + " and extends it to " + ttl_to_extend);
    res.send("Trying to lock the resources " + resources + " with a ttl of " + ttl + " and extends it to " + ttl_to_extend);
    redLockFactory.lockOneResource(resource, ttl);
    redLockFactory.lockMultipleResources(resources, ttl, ttl_to_extend);
    console.log("Trying to access to %s resource...", resources[0]);
    redLockFactory.lockOneResource(resources[0], ttl);
    console.log("Done");
});

/**
 * This function starts the server
 */
server.listen(process.env.PORT, () => console.log(`Server listening in port ${process.env.PORT}`));
