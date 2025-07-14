# Redis Redlock Samples

This repository contains various configurations and test samples for using the [Redlock algorithm](https://redis.io/docs/latest/develop/clients/patterns/distributed-locks/#the-redlock-algorithm) with Redis, implemented in multiple programming languages:

- **Java**
- **Node.js**
- **PHP**
- **Python**

Each language-specific implementation is organized in its own directory and includes its own README file with detailed instructions and usage examples.

## Project Structure

```
redis_redlock_java/     # Java implementation
redis_redlock_node/     # Node.js implementation
redis_redlock_php/      # PHP implementation
redis_redlock_python/   # Python implementation
```

## About Redlock

Redlock is an algorithm designed by Redis for distributed locking, ensuring safe and reliable locks across multiple Redis instances. This repository demonstrates how to implement and test Redlock in different environments and languages.

## Generic Installation Guide

Each subproject contains its own dependencies and setup instructions. Please refer to the respective `README.md` files inside each language folder for detailed steps. Below is a generic guide to get you started:

1. **Clone this repository:**
   ```sh
   git clone https://github.com/SergioFdezRc/redis-redlock-samples
   cd redis-redlock-samples
   ```

2. **Choose your language:**
   Navigate to the folder of the language you want to use (e.g., `redis_redlock_java`, `redis_redlock_node`, etc.).

3. **Follow the language-specific instructions:**
   Each folder contains a `README.md` file with installation and usage instructions tailored for that language.

4. **Redis Server:**
   Ensure you have a Redis server running locally or accessible remotely. Some samples may require multiple Redis instances to fully test the Redlock algorithm.

## Requirements

- [Git](https://git-scm.com/)
- [Redis](https://redis.io/download)
- Language-specific runtimes and package managers:
  - Java (JDK 8+ and Maven)
  - Node.js (v14+ and npm)
  - PHP (7.4+ and Composer)
  - Python (3.7+ and pip)

## Contributing

Contributions are welcome! Feel free to submit issues or pull requests to improve the samples or add new language implementations.

---

For more information about the Redlock algorithm, visit the [official Redis documentation](https://redis.io/docs/latest/develop/clients/patterns/distributed-locks/). 