"""Realtime inference pipeline."""
from __future__ import annotations

import argparse
import time
from collections import deque
from pathlib import Path
from typing import Deque, Optional

import cv2
import numpy as np

from utils.io import load_config, load_label_map
from utils.logger import setup_logger

logger = setup_logger()

try:
    import mediapipe as mp

    mp_hands = mp.solutions.hands
except Exception:  # pragma: no cover - optional dep
    mp_hands = None


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Realtime inference for Sign2Speech")
    parser.add_argument("--config", default="config.yaml")
    parser.add_argument("--label-map", default="assets/label_map.json")
    parser.add_argument("--models-dir", default="models")
    parser.add_argument("--autospeak", action="store_true", help="Automatically speak stabilized predictions")
    return parser.parse_args()


def load_model(model_type: str, models_dir: Path):
    if model_type == "cnn-image":
        import tensorflow as tf

        return tf.keras.models.load_model(models_dir / "cnn_model.h5")
    import joblib

    return joblib.load(models_dir / "mlp_model.pkl")


class PredictionDebouncer:
    def __init__(self, required_frames: int):
        self.required = required_frames
        self.buffer: Deque[str] = deque(maxlen=required_frames)
        self.last_label: Optional[str] = None

    def push(self, label: str) -> Optional[str]:
        self.buffer.append(label)
        if len(self.buffer) == self.required and len(set(self.buffer)) == 1:
            stable_label = self.buffer[0]
            if stable_label != self.last_label:
                self.last_label = stable_label
                return stable_label
        return None


def preprocess_frame(frame: np.ndarray, img_size: int) -> np.ndarray:
    frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    resized = cv2.resize(frame_rgb, (img_size, img_size))
    normalized = resized.astype("float32") / 255.0
    return np.expand_dims(normalized, axis=0)


def extract_landmarks(frame: np.ndarray, detector) -> Optional[np.ndarray]:
    frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    results = detector.process(frame_rgb)
    if not results.multi_hand_landmarks:
        return None
    hand = results.multi_hand_landmarks[0]
    coords = np.array([[lm.x, lm.y, lm.z] for lm in hand.landmark])
    wrist = coords[0]
    centered = coords - wrist
    scale = np.linalg.norm(centered, axis=1).max() or 1.0
    normalized = centered / scale
    return np.expand_dims(normalized.flatten(), axis=0)


def main() -> None:
    args = parse_args()
    cfg = load_config(args.config)
    label_map = load_label_map(args.label_map)
    inv_label_map = {idx: label for label, idx in label_map.items()}
    classes = [inv_label_map[idx] for idx in sorted(inv_label_map)]

    model_type = cfg["modelType"]
    model = load_model(model_type, Path(args.models_dir))

    detector = None
    if model_type == "landmark-mlp":
        if mp_hands is None:
            raise RuntimeError("mediapipe is required for landmark inference")
        detector = mp_hands.Hands(static_image_mode=False, max_num_hands=1, min_detection_confidence=0.5)

    cap = cv2.VideoCapture(cfg["video"]["deviceIndex"])
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, cfg["video"]["width"])
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, cfg["video"]["height"])

    debouncer = PredictionDebouncer(cfg["debounceFrames"])
    last_spoken = None

    from tts import speak_text  # local import to avoid heavy deps when unused

    logger.info("Starting realtime inference (%s)", model_type)
    fps_clock = deque(maxlen=30)

    try:
        while True:
            start = time.time()
            ret, frame = cap.read()
            if not ret:
                logger.warning("Frame grab failed")
                continue

            input_tensor = None
            if model_type == "cnn-image":
                input_tensor = preprocess_frame(frame, cfg["model"]["cnn"]["imgSize"])
                probs = model.predict(input_tensor, verbose=0)[0]
            else:
                landmarks = extract_landmarks(frame, detector)
                if landmarks is None:
                    cv2.imshow("Sign2Speech", frame)
                    if cv2.waitKey(1) & 0xFF == ord("q"):
                        break
                    continue
                probs = model.predict_proba(landmarks)[0]

            best_idx = int(np.argmax(probs))
            best_label = classes[best_idx]
            best_score = float(probs[best_idx])

            stable = debouncer.push(best_label)
            if stable and args.autospeak and best_score >= cfg["minConfidence"] and stable != last_spoken:
                speak_text(stable, provider=cfg["tts"]["provider"], language=cfg["tts"]["language"])
                last_spoken = stable

            overlay = frame.copy()
            cv2.putText(overlay, f"Pred: {best_label} ({best_score:.2f})", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
            if stable:
                cv2.putText(overlay, f"Stable: {stable}", (10, 60), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (255, 255, 0), 2)

            fps_clock.append(time.time() - start)
            if fps_clock:
                fps = len(fps_clock) / sum(fps_clock)
                cv2.putText(overlay, f"FPS: {fps:.1f}", (10, 90), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 255), 2)

            cv2.imshow("Sign2Speech", overlay)
            if cv2.waitKey(1) & 0xFF == ord("q"):
                break
    finally:
        cap.release()
        cv2.destroyAllWindows()
        logger.info("Realtime inference stopped")


if __name__ == "__main__":
    main()
