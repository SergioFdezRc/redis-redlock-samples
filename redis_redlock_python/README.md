# Redis Redlock Python sample

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [References](#references)

## Overview
**Author:** Sergio Fernández Rincón  
**Version:** 1.0  
**Course:** Distributed Operating Systems  
**Degree:** Master's in Computer Engineering  
**University:** University of Extremadura

## Description
This project is designed to test the [Redis Redlock](https://redis.io/topics/distlock/) algorithm implementation in Python. It aims to perform a series of experiments and checks to understand the behavior and reliability of distributed locks using Redis and the Redlock algorithm.

## Features
- Test and evaluate the Redlock algorithm in a Python environment
- Analyze lock acquisition, release, and failure scenarios
- Provide sample code and utilities for Redlock usage

## Requirements
- Python 3.7+
- Redis server (local or remote)
- See `requirements.txt` for Python dependencies

## Usage
1. Install dependencies:
   ```bash
   pip install -r requirements.txt
   ```
2. Configure your Redis connection in `utils/config.py` if needed.
3. Run the main test script:
   ```bash
   python -m redlock_test.main
   ```

## Project Structure
- `redlock_test/` — Main test scripts and Redlock logic
- `utils/` — Configuration and utility modules
- `requirements.txt` — Python dependencies

## References
- [Redis Redlock documentation](https://redis.io/topics/distlock/)
- [redlock-py library](https://github.com/SPSCommerce/redlock-py)

---
