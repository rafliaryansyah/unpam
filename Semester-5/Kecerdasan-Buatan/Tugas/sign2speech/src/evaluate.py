"""Evaluation script for trained models."""
from __future__ import annotations

import argparse
from pathlib import Path

import numpy as np
from sklearn.metrics import classification_report, confusion_matrix

from utils.io import load_label_map, load_numpy_dataset, save_json
from utils.logger import setup_logger
from utils.viz import plot_confusion_matrix


logger = setup_logger()


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Evaluate Sign2Speech models")
    parser.add_argument("--model", choices=["cnn-image", "landmark-mlp"], required=True)
    parser.add_argument("--dataset", required=True)
    parser.add_argument("--label-map", default="assets/label_map.json")
    parser.add_argument("--models-dir", default="models")
    parser.add_argument("--reports-dir", default="reports")
    return parser.parse_args()


def load_model(model_type: str, models_dir: Path):
    if model_type == "cnn-image":
        import tensorflow as tf

        return tf.keras.models.load_model(models_dir / "cnn_model.h5")
    import joblib

    return joblib.load(models_dir / "mlp_model.pkl")


def main() -> None:
    args = parse_args()
    label_map = load_label_map(args.label_map)
    inv_label_map = {idx: label for label, idx in label_map.items()}
    classes = [inv_label_map[idx] for idx in sorted(inv_label_map)]

    X, y = load_numpy_dataset(args.dataset)
    model = load_model(args.model, Path(args.models_dir))

    if args.model == "cnn-image":
        probs = model.predict(X, verbose=0)
    else:
        probs = model.predict_proba(X)
    preds = np.argmax(probs, axis=1)

    report = classification_report(y, preds, target_names=classes, output_dict=True)
    cm = confusion_matrix(y, preds)

    reports_dir = Path(args.reports_dir)
    reports_dir.mkdir(parents=True, exist_ok=True)
    plot_confusion_matrix(cm, classes, reports_dir / "confusion_matrix_eval.png")

    save_json(
        {
            "overall": {"accuracy": report["accuracy"], "macroF1": report["macro avg"]["f1-score"]},
            "perClass": {
                cls: {
                    "precision": report[cls]["precision"],
                    "recall": report[cls]["recall"],
                    "f1": report[cls]["f1-score"],
                }
                for cls in classes
            },
            "confusionMatrix": cm.tolist(),
        },
        reports_dir / "metrics_eval.json",
    )
    logger.info("Evaluation metrics saved to %s", reports_dir / "metrics_eval.json")


if __name__ == "__main__":
    main()
