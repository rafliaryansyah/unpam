"""Training entrypoint for Sign2Speech models."""
from __future__ import annotations

import argparse
import json
from pathlib import Path
from typing import Any, Dict

import numpy as np
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.model_selection import train_test_split

from utils.io import load_config, load_label_map, load_numpy_dataset, save_json
from utils.logger import setup_logger
from utils.viz import plot_confusion_matrix, plot_training_curves


logger = setup_logger()


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Train sign classification models")
    parser.add_argument("--model", choices=["cnn-image", "landmark-mlp"], required=True)
    parser.add_argument("--dataset", help="Path to processed dataset (.npy)")
    parser.add_argument("--config", default="config.yaml")
    parser.add_argument("--label-map", default="assets/label_map.json")
    parser.add_argument("--models-dir", default="models")
    parser.add_argument("--reports-dir", default="reports")
    return parser.parse_args()


def train_cnn(
    X_train: np.ndarray,
    y_train: np.ndarray,
    X_test: np.ndarray,
    y_test: np.ndarray,
    cfg: Dict[str, Any],
    models_dir: Path,
    reports_dir: Path,
    classes: list[str],
) -> None:
    import tensorflow as tf

    img_size = cfg["model"]["cnn"]["imgSize"]
    num_classes = len(classes)
    model = tf.keras.Sequential(
        [
            tf.keras.layers.Input(shape=(img_size, img_size, X_train.shape[-1])),
            tf.keras.layers.Conv2D(32, 3, padding="same"),
            tf.keras.layers.BatchNormalization(),
            tf.keras.layers.ReLU(),
            tf.keras.layers.MaxPooling2D(),
            tf.keras.layers.Conv2D(64, 3, padding="same"),
            tf.keras.layers.BatchNormalization(),
            tf.keras.layers.ReLU(),
            tf.keras.layers.MaxPooling2D(),
            tf.keras.layers.Conv2D(128, 3, padding="same"),
            tf.keras.layers.BatchNormalization(),
            tf.keras.layers.ReLU(),
            tf.keras.layers.GlobalAveragePooling2D(),
            tf.keras.layers.Dense(128, activation="relu"),
            tf.keras.layers.Dropout(0.3),
            tf.keras.layers.Dense(num_classes, activation="softmax"),
        ]
    )

    model.compile(
        optimizer=tf.keras.optimizers.Adam(cfg["model"]["cnn"]["learningRate"]),
        loss="sparse_categorical_crossentropy",
        metrics=["accuracy"],
    )

    callbacks = [
        tf.keras.callbacks.EarlyStopping(patience=5, restore_best_weights=True),
        tf.keras.callbacks.ReduceLROnPlateau(patience=3, factor=0.5),
    ]

    train_val_total = cfg["data"]["split"]["train"] + cfg["data"]["split"]["val"]
    val_fraction = cfg["data"]["split"]["val"] / train_val_total

    history = model.fit(
        X_train,
        y_train,
        validation_split=val_fraction,
        epochs=cfg["model"]["cnn"]["epochs"],
        batch_size=cfg["model"]["cnn"]["batchSize"],
        callbacks=callbacks,
        verbose=1,
    )

    test_loss, test_acc = model.evaluate(X_test, y_test, verbose=0)
    preds = np.argmax(model.predict(X_test, verbose=0), axis=1)

    report = classification_report(y_test, preds, target_names=classes, output_dict=True)
    cm = confusion_matrix(y_test, preds)

    models_dir.mkdir(parents=True, exist_ok=True)
    reports_dir.mkdir(parents=True, exist_ok=True)

    model_path = models_dir / "cnn_model.h5"
    model.save(model_path)

    plot_training_curves(history.history, reports_dir / "training_curves.png")
    plot_confusion_matrix(cm, classes, reports_dir / "confusion_matrix.png")

    val_acc = history.history.get("val_accuracy")
    best_epoch = int(np.argmax(val_acc)) + 1 if val_acc else len(history.history.get("loss", []))

    metrics_payload = {
        "overall": {
            "accuracy": float(report["accuracy"]),
            "macroF1": float(np.mean([report[c]["f1-score"] for c in classes])),
        },
        "perClass": {
            cls: {
                "precision": report[cls]["precision"],
                "recall": report[cls]["recall"],
                "f1": report[cls]["f1-score"],
            }
            for cls in classes
        },
        "confusionMatrix": cm.tolist(),
        "bestEpoch": best_epoch,
    }
    save_json(metrics_payload, reports_dir / "metrics.json")
    logger.info("CNN model saved to %s (test_acc=%.3f, test_loss=%.3f)", model_path, test_acc, test_loss)


def train_mlp(
    X_train: np.ndarray,
    y_train: np.ndarray,
    X_test: np.ndarray,
    y_test: np.ndarray,
    cfg: Dict[str, Any],
    models_dir: Path,
    reports_dir: Path,
    classes: list[str],
) -> None:
    from sklearn.neural_network import MLPClassifier
    from sklearn.pipeline import Pipeline
    from sklearn.preprocessing import StandardScaler
    import joblib

    clf = Pipeline(
        [
            ("scaler", StandardScaler()),
            (
                "mlp",
                MLPClassifier(
                    hidden_layer_sizes=tuple(cfg["model"]["mlp"]["hiddenLayers"]),
                    activation="relu",
                    learning_rate_init=cfg["model"]["mlp"]["learningRate"],
                    max_iter=cfg["model"]["mlp"]["maxIter"],
                    random_state=cfg["randomSeed"],
                ),
            ),
        ]
    )
    clf.fit(X_train, y_train)
    preds = clf.predict(X_test)

    report = classification_report(y_test, preds, target_names=classes, output_dict=True)
    cm = confusion_matrix(y_test, preds)

    models_dir.mkdir(parents=True, exist_ok=True)
    reports_dir.mkdir(parents=True, exist_ok=True)

    joblib.dump(clf, models_dir / "mlp_model.pkl")
    plot_confusion_matrix(cm, classes, reports_dir / "confusion_matrix.png")

    metrics_payload = {
        "overall": {"accuracy": float(report["accuracy"]), "macroF1": report["macro avg"]["f1-score"]},
        "perClass": {
            cls: {
                "precision": report[cls]["precision"],
                "recall": report[cls]["recall"],
                "f1": report[cls]["f1-score"],
            }
            for cls in classes
        },
        "confusionMatrix": cm.tolist(),
    }
    save_json(metrics_payload, reports_dir / "metrics.json")
    logger.info("MLP model saved to %s (test_acc=%.3f)", models_dir / "mlp_model.pkl", report["accuracy"])


def main() -> None:
    args = parse_args()
    cfg = load_config(args.config)
    label_map = load_label_map(args.label_map)
    inv_label_map = {idx: label for label, idx in label_map.items()}
    classes = [inv_label_map[idx] for idx in sorted(inv_label_map)]

    X, y = load_numpy_dataset(args.dataset)
    X_train, X_test, y_train, y_test = train_test_split(
        X,
        y,
        test_size=cfg["data"]["split"]["test"],
        random_state=cfg["randomSeed"],
        stratify=y,
    )

    models_dir = Path(args.models_dir)
    reports_dir = Path(args.reports_dir)

    if args.model == "cnn-image":
        train_cnn(X_train, y_train, X_test, y_test, cfg, models_dir, reports_dir, classes)
    else:
        train_mlp(X_train, y_train, X_test, y_test, cfg, models_dir, reports_dir, classes)


if __name__ == "__main__":
    main()
