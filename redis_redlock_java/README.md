# Redis Redlock Java Sample

## Table of Contents
- [Overview](#overview)
- [Installation](#installation)
- [Prerequisites](#prerequisites)
- [Getting Started](#getting-started)
  - [Start a Redis Server](#1-start-a-redis-server)
  - [Check Maven Dependencies](#2-check-maven-dependencies)
  - [Build the Project](#3-build-the-project)
  - [Run the Example](#4-run-the-example)
  - [Output](#5-output)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Overview
This project demonstrates how to connect to a Redis server and use the Redlock distributed locking algorithm in Java using Redisson.

**Author:** Sergio Fernández Rincón  
**Version:** 1.0  
**Course:** Distributed Operating Systems  
**Degree:** Master's in Computer Engineering  
**University:** University of Extremadura

## Installation

1. **Clone the repository from GitHub:**
   ```sh
   git clone https://github.com/SergioFdezRc/redis_redlock_java.git
   cd redis_redlock_java
   ```

2. **Follow the steps below to set up prerequisites, build, and run the project.**

## Prerequisites

- **Java 8 or higher**
- **Maven** (for building and running the project)
- **Redis server** running locally on `localhost:6379`

## Getting Started

### 1. Start a Redis Server

You need a running Redis instance. By default, the project connects to `localhost:6379`.

#### Option A: Native Installation
- Download and install Redis from [https://redis.io/download](https://redis.io/download)
- Start the server:
  ```sh
  redis-server
  ```

#### Option B: Using Docker
If you have Docker installed, you can run Redis with:
```sh
docker run --name redis-test -p 6379:6379 -d redis
```

### 2. Check Maven Dependencies

Ensure your `pom.xml` includes the following dependency for Redisson:

```xml
<dependency>
    <groupId>org.redisson</groupId>
    <artifactId>redisson</artifactId>
    <version>3.23.4</version>
</dependency>
```

If you want to run the project using Maven, make sure you have the `exec-maven-plugin` in your `pom.xml`:

```xml
<plugin>
    <groupId>org.codehaus.mojo</groupId>
    <artifactId>exec-maven-plugin</artifactId>
    <version>3.1.0</version>
</plugin>
```

### 3. Build the Project

From the project root directory, run:

```sh
mvn clean compile
```

### 4. Run the Example

Execute the main class using Maven:

```sh
mvn exec:java -Dexec.mainClass="RedlockTesting"
```

If your class is in a package, use the fully qualified class name (e.g., `com.example.RedlockTesting`).

### 5. Output

You should see output in the console showing Redis map operations and the distributed lock (Redlock) demonstration.

---

## Troubleshooting

- **Redis not running:** Ensure Redis is running on `localhost:6379` before starting the Java application.
- **Dependency issues:** Run `mvn clean install` to resolve dependencies.
- **Port conflicts:** If you have another service on port 6379, stop it or change the Redis port and update the code accordingly.

---

## License

This project is for educational and demonstration purposes.