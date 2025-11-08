"""I/O helpers for Sign2Speech pipeline."""
from __future__ import annotations

import json
from pathlib import Path
from typing import Any, Dict, Tuple

import numpy as np
import yaml


ROOT_DIR = Path(__file__).resolve().parents[2]


def load_config(path: Path | str) -> Dict[str, Any]:
    cfg_path = (ROOT_DIR / path).resolve() if isinstance(path, str) else path
    with open(cfg_path, "r", encoding="utf-8") as f:
        return yaml.safe_load(f)


def load_label_map(path: Path | str) -> Dict[str, int]:
    label_path = (ROOT_DIR / path).resolve() if isinstance(path, str) else path
    with open(label_path, "r", encoding="utf-8") as f:
        return json.load(f)


def invert_label_map(label_map: Dict[str, int]) -> Dict[int, str]:
    return {idx: label for label, idx in label_map.items()}


def save_json(data: Dict[str, Any], path: Path | str) -> None:
    file_path = Path(path)
    file_path.parent.mkdir(parents=True, exist_ok=True)
    with open(file_path, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)


def load_numpy_dataset(dataset_path: Path | str) -> Tuple[np.ndarray, np.ndarray]:
    np_path = Path(dataset_path)
    data = np.load(np_path, allow_pickle=True).item()
    return data["X"], data["y"]


def save_numpy_dataset(X: np.ndarray, y: np.ndarray, path: Path | str) -> None:
    np_path = Path(path)
    np_path.parent.mkdir(parents=True, exist_ok=True)
    np.save(np_path, {"X": X, "y": y})
