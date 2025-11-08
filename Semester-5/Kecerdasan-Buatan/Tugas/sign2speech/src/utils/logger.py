"""Logging helpers for Sign2Speech."""
from __future__ import annotations

import logging
from pathlib import Path
from typing import Optional


def setup_logger(name: str = "sign2speech", log_file: Optional[Path] = None) -> logging.Logger:
    """Configure and return a logger instance."""
    logger = logging.getLogger(name)
    if logger.handlers:
        return logger

    logger.setLevel(logging.INFO)
    formatter = logging.Formatter(
        "%(asctime)s | %(levelname)s | %(name)s | %(message)s", "%Y-%m-%d %H:%M:%S"
    )

    stream_handler = logging.StreamHandler()
    stream_handler.setFormatter(formatter)
    logger.addHandler(stream_handler)

    if log_file:
        log_file.parent.mkdir(parents=True, exist_ok=True)
        file_handler = logging.FileHandler(log_file)
        file_handler.setFormatter(formatter)
        logger.addHandler(file_handler)

    return logger


def get_logger(name: str = "sign2speech") -> logging.Logger:
    return logging.getLogger(name)
