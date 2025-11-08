"""Preprocess raw gesture data into numpy arrays."""
from __future__ import annotations

import argparse
import sys
from pathlib import Path
from typing import List, Tuple

import cv2
import numpy as np

from utils.io import invert_label_map, load_label_map, save_numpy_dataset
from utils.logger import setup_logger


logger = setup_logger()

try:
    import mediapipe as mp

    mp_hands = mp.solutions.hands
except Exception:  # pragma: no cover - optional dep
    mp_hands = None


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Preprocess gesture dataset")
    parser.add_argument("--mode", choices=["cnn", "landmark"], required=True)
    parser.add_argument("--raw-dir", default="data/raw")
    parser.add_argument("--processed-dir", default="data/processed")
    parser.add_argument("--img-size", type=int, default=128)
    parser.add_argument("--label-map", default="assets/label_map.json")
    return parser.parse_args()


def collect_images(raw_dir: Path, class_name: str) -> List[Path]:
    class_dir = raw_dir / class_name
    if not class_dir.exists():
        logger.warning("Class folder %s not found, skipping", class_name)
        return []
    images = list(class_dir.glob("*.jpg")) + list(class_dir.glob("*.png"))
    return sorted(images)


def preprocess_image(path: Path, target_size: int) -> np.ndarray:
    img = cv2.imread(str(path))
    if img is None:
        raise ValueError(f"Unable to read {path}")
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = cv2.resize(img, (target_size, target_size))
    img = img.astype("float32") / 255.0
    return img


def preprocess_landmark(path: Path, detector) -> np.ndarray | None:
    img = cv2.imread(str(path))
    if img is None:
        raise ValueError(f"Unable to read {path}")
    image_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    results = detector.process(image_rgb)
    if not results.multi_hand_landmarks:
        return None
    hand = results.multi_hand_landmarks[0]
    coords = np.array([[lm.x, lm.y, lm.z] for lm in hand.landmark])
    wrist = coords[0]
    centered = coords - wrist
    scale = np.linalg.norm(centered, axis=1).max()
    if scale == 0:
        scale = 1.0
    normalized = centered / scale
    return normalized.flatten()


def build_dataset(mode: str, raw_dir: Path, classes: List[str], img_size: int) -> Tuple[np.ndarray, np.ndarray]:
    X: List[np.ndarray] = []
    y: List[int] = []

    if mode == "landmark" and mp_hands is None:
        logger.error("mediapipe is required for landmark mode. Install mediapipe first.")
        sys.exit(1)

    detector = None
    if mode == "landmark":
        detector = mp_hands.Hands(static_image_mode=True, max_num_hands=1, min_detection_confidence=0.5)

    for class_idx, class_name in enumerate(classes):
        images = collect_images(raw_dir, class_name)
        logger.info("Found %d images for class %s", len(images), class_name)
        for path in images:
            if mode == "cnn":
                X.append(preprocess_image(path, img_size))
            else:
                landmarks = preprocess_landmark(path, detector)
                if landmarks is None:
                    continue
                X.append(landmarks)
            y.append(class_idx)

    if not X:
        raise RuntimeError("No data processed. Check your raw dataset.")

    X_arr = np.stack(X)
    y_arr = np.array(y)
    return X_arr, y_arr


def main() -> None:
    args = parse_args()
    raw_dir = Path(args.raw_dir)
    processed_dir = Path(args.processed_dir)
    label_map = load_label_map(args.label_map)
    classes = list(label_map.keys())
    inv_label_map = invert_label_map(label_map)
    classes_sorted = [inv_label_map[idx] for idx in sorted(inv_label_map.keys())]

    X, y = build_dataset(args.mode, raw_dir, classes_sorted, args.img_size)

    suffix = "cnn" if args.mode == "cnn" else "landmark"
    out_path = processed_dir / f"{suffix}_dataset.npy"
    save_numpy_dataset(X, y, out_path)
    logger.info("Saved processed dataset to %s (X=%s, y=%s)", out_path, X.shape, y.shape)


if __name__ == "__main__":
    main()
