import os

Config = {
    "HOST_URI": os.getenv("HOST_URI", "127.0.0.1"),
    "PORT": int(os.getenv("PORT", 6379))
}