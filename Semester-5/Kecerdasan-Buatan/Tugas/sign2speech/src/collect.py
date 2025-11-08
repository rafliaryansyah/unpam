"""Dataset collector using webcam."""
from __future__ import annotations

import argparse
import time
from pathlib import Path
from typing import Tuple

import cv2

from utils.logger import setup_logger


logger = setup_logger()


DEF_INTERVAL = 0.4  # seconds


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Collect sign image dataset from webcam")
    parser.add_argument("class_name", help="Label/gesture name to capture")
    parser.add_argument("count", type=int, help="Number of samples to capture")
    parser.add_argument("--output", default="data/raw", help="Root folder for raw images")
    parser.add_argument("--interval", type=float, default=DEF_INTERVAL, help="Capture interval in seconds for auto mode")
    parser.add_argument("--device", type=int, default=0, help="Camera device index")
    parser.add_argument("--auto", action="store_true", help="Capture automatically every interval instead of waiting for key press")
    return parser.parse_args()


def ensure_dirs(output_root: Path, class_name: str) -> Path:
    class_dir = output_root / class_name
    class_dir.mkdir(parents=True, exist_ok=True)
    return class_dir


def save_frame(frame, path: Path) -> None:
    cv2.imwrite(str(path), frame)


def overlay_text(frame, text: str, pos: Tuple[int, int] = (20, 30)) -> None:
    cv2.putText(frame, text, pos, cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2, cv2.LINE_AA)


def main() -> None:
    args = parse_args()
    output_root = Path(args.output)
    class_dir = ensure_dirs(output_root, args.class_name)

    cap = cv2.VideoCapture(args.device)
    if not cap.isOpened():
        logger.error("Unable to open camera index %s", args.device)
        return

    saved = 0
    last_capture = 0.0
    paused = False

    logger.info("Starting capture for class '%s' (target=%d)", args.class_name, args.count)
    logger.info("Press SPACE to capture (manual), 'a' to toggle auto, 'p' to pause, 'q' to quit")

    try:
        while saved < args.count:
            ret, frame = cap.read()
            if not ret:
                logger.warning("Frame grab failed, retrying...")
                continue

            overlay_text(frame, f"Class: {args.class_name}")
            overlay_text(frame, f"Saved: {saved}/{args.count}", (20, 60))
            overlay_text(frame, "Mode: auto" if args.auto else "Mode: manual", (20, 90))
            if paused:
                overlay_text(frame, "PAUSED", (20, 120))

            cv2.imshow("Sign2Speech Collector", frame)
            key = cv2.waitKey(1) & 0xFF

            if key == ord("q"):
                logger.info("Exit requested by user")
                break
            if key == ord("p"):
                paused = not paused
            if key == ord("a"):
                args.auto = not args.auto
            if paused:
                continue

            should_capture = False
            if args.auto and (time.time() - last_capture) >= args.interval:
                should_capture = True
            elif not args.auto and key == ord(" "):
                should_capture = True

            if should_capture:
                filename = class_dir / f"{args.class_name}_{saved:04d}.jpg"
                save_frame(frame, filename)
                saved += 1
                last_capture = time.time()
    finally:
        cap.release()
        cv2.destroyAllWindows()
        logger.info("Capture finished (%d/%d saved)", saved, args.count)


if __name__ == "__main__":
    main()
