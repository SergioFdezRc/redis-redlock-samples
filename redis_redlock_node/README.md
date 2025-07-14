# Redis Redlock Node Example

This project demonstrates a basic implementation of distributed locks using the Redlock algorithm with three Redis clients in Node.js.

**Author:** Sergio Fernández Rincón  
**Version:** 1.0  
**Course:** Distributed Operating Systems  
**Degree:** Master's in Computer Engineering  
**University:** University of Extremadura

## Table of Contents
- [Overview](#overview)
- [Architecture](#architecture)
- [Setup](#setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [API](#api)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [References](#references)

## Overview
Redlock is an algorithm for distributed locks with Redis, designed to ensure that only one process can hold a lock for a given resource at a time, even in a distributed environment. This project provides sample code for acquiring, extending, and releasing locks using the `redlock` npm package and three independent Redis nodes.

## Architecture
- **Node.js** application
- **Three Redis clients** (simulating three Redis nodes)
- **Redlock** for distributed locking

```
+-------------------+
|   Node.js App     |
+-------------------+
         |
         v
+-------------------+
|   Redlock (lib)   |
+-------------------+
   |     |     |
   v     v     v
Redis1 Redis2 Redis3
```

## Setup

### Prerequisites
- Node.js >= 14.x
- Three running Redis servers (can be local or remote)
- npm

### Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/SergioFdezRc/redis_redlock_node.git
   cd redis_redlock_node
   ```
2. Install dependencies:
   ```sh
   npm install
   ```

## Configuration

The file `app/redis/Redlock_config.js` configures three Redis clients:
```js
let client1 = require('redis').createClient(6379, '127.0.0.1');
let client2 = require('redis').createClient(6379, '127.0.0.2');
let client3 = require('redis').createClient(6379, '127.0.0.3');
let Redlock = require('redlock');

const redlock = new Redlock([
    client1, client2, client3
], { /* options */ });

export default redlock;
```
- Make sure you have three Redis servers running at the specified IPs and ports.
- Adjust the IPs/ports if your Redis servers are running elsewhere.

## Usage

- The main locking logic is in `app/redis/RedLockFactory.js`.
- Example usage:
  ```js
  import redLockFactory from './app/redis/RedLockFactory';
  redLockFactory.lockOneResource('resource_key', 1000);
  ```
- See the methods:
  - `lockOneResource(resource, ttl)`
  - `lockMultipleResources(resources, ttl, ttl_to_extend)`
  - `lockAndExtendResource(resource, ttl, ttl_to_extend)`

## API

### lockOneResource(resource, ttl)
Locks a single resource for a given TTL (ms), performs a task, and releases the lock.

### lockMultipleResources(resources, ttl, ttl_to_extend)
Locks multiple resources, extends the TTL, and then releases the lock.

### lockAndExtendResource(resource, ttl, ttl_to_extend)
Locks a resource, extends the TTL while working, and releases the lock.

## Testing
- Ensure all three Redis servers are running.
- Run the sample app:
  ```sh
  node start.js
  ```
- Check the console output for lock acquisition and release logs.

## Troubleshooting
- **Connection errors:** Ensure Redis servers are running and accessible at the configured IPs/ports.
- **Lock acquisition fails:** Check network connectivity and Redis logs.
- **Module not found:** Run `npm install` to install dependencies.

## References
- [Redlock Algorithm](https://redis.io/docs/latest/develop/clients/patterns/distributed-locks/#the-redlock-algorithm)
- [redlock npm package](https://www.npmjs.com/package/redlock)
- [Node Redis](https://www.npmjs.com/package/redis)