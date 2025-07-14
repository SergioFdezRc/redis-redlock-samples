from redlock import Redlock, Lock

from utils.config import Config


def check_dlm_connections(function):
    """
    Check that the connection is active before launching a function

    :param function: the function to be launched
    :return: the function wrapped
    """

    def wrapper(*args):
        if args[0].dlm is not None:
            return function(*args)
        else:
            print("[ERROR] You must create a connection first")

    return wrapper


class RedLock:

    def __init__(self) -> None:
        self.dlm = None

    def create_connection(self):
        """
        It creates a Redlock instance with a connection server with host and port indicated by constants values
        :return: an instance of the dlm
        """
        self.dlm = Redlock([{"host": Config["HOST_URI"], "port": Config["PORT"], "db": 0},
                            {"host": Config["HOST_URI"], "port": Config["PORT"], "db": 1},
                            {"host": Config["HOST_URI"], "port": Config["PORT"], "db": 2}])

    @check_dlm_connections
    def add_server_to_dlm(self):
        pass

    @check_dlm_connections
    def create_lock(self, lock_name: str, ttl: int = 1000) -> Lock:
        """
        It creates a new lock of RedLock
        :param lock_name: the name of the lock
        :param ttl: the time to live
        :return: a lock with name :name and a ttl of :ttl
        """
        return self.dlm.lock(lock_name, ttl)

    @check_dlm_connections
    def list_clients(self, db_id: int) -> list:
        """
        List the client params of a given db index.
        :param db_id: the index of the db
        :return:
        """
        try:
            return self.dlm.servers[db_id].execute_command("CLIENT", "LIST")
        except Exception as e:
            print("[ERROR] the server with id %d does not exists." % db_id, e)
            return []

    @check_dlm_connections
    def unlock_a_lock(self, lock: Lock) -> bool:
        """
        Release a given lock
        :param lock: the lock to be released
        :return: none
        """
        try:
            self.dlm.unlock(lock)
            return True
        except Exception:
            pass
        return False
