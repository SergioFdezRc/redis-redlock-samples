from redlock_test.RedLock import RedLock


def create_n_locks(rl: RedLock, n: int, name: str, duration: int) -> None:
    """
    Create n locks with the same name and duration

    :param n: number of locks to create
    :param name: name of the lock
    :param duration: duration of the lock
    :return: None
    """
    for i in range(0, n):
        lock = rl.create_lock(name, duration)
        if not lock:
            print("[WARNING] The lock %s is still busy" % name)
        else:
            print("[INFO] The lock %s is free" % name)


if __name__ == '__main__':
    rl = RedLock()
    rl.create_connection()
    print("[INFO] Creating the first lock.")
    lock_name = "tests"
    lock = rl.create_lock(lock_name, 30000)
    print("[INFO] Lock created: ", lock_name)
    print("[INFO] Trying to create more locks with the same name...")

    create_n_locks(rl, 4, lock_name, 1000)

    print("[INFO] Releasing the lock %s..." % lock_name)
    rl.unlock_a_lock(lock)
    print("[INFO] The lock has been released. The system will try to create some locks again...")

    create_n_locks(rl, 3, lock_name, 1000)
