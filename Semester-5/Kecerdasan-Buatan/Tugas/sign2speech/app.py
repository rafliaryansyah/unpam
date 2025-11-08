from __future__ import annotations

from pathlib import Path
from typing import Optional

import av
import cv2
import numpy as np
import streamlit as st
from streamlit_webrtc import RTCConfiguration, VideoProcessorBase, webrtc_streamer

from src.tts import speak_text
from src.utils.io import load_config, load_label_map

try:
    import mediapipe as mp

    mp_hands = mp.solutions.hands
except Exception:  # pragma: no cover - optional dep
    mp_hands = None


st.set_page_config(page_title="Sign2Speech", layout="wide")


@st.cache_resource
def load_models(cfg_path: Path, models_dir: Path):
    cfg = load_config(cfg_path)
    label_map = load_label_map("assets/label_map.json")
    inv = {idx: label for label, idx in label_map.items()}
    classes = [inv[idx] for idx in sorted(inv)]

    if cfg["modelType"] == "cnn-image":
        import tensorflow as tf

        model = tf.keras.models.load_model(models_dir / "cnn_model.h5")
    else:
        import joblib

        model = joblib.load(models_dir / "mlp_model.pkl")
    return cfg, classes, model


class SignVideoProcessor(VideoProcessorBase):
    def __init__(self, cfg, classes, model, autospeak: bool):
        self.cfg = cfg
        self.classes = classes
        self.model = model
        self.autospeak = autospeak
        self.last_prediction: Optional[tuple[str, float]] = None
        self.debounce_buffer: list[str] = []
        self.last_spoken: Optional[str] = None
        self.detector = None
        if cfg["modelType"] == "landmark-mlp":
            if mp_hands is None:
                raise RuntimeError("mediapipe is required for landmark mode")
            self.detector = mp_hands.Hands(static_image_mode=False, max_num_hands=1)

    def _preprocess_frame(self, frame: np.ndarray) -> Optional[np.ndarray]:
        if self.cfg["modelType"] == "cnn-image":
            img_size = self.cfg["model"]["cnn"]["imgSize"]
            rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            resized = cv2.resize(rgb, (img_size, img_size))
            normalized = resized.astype("float32") / 255.0
            return np.expand_dims(normalized, 0)
        return self._extract_landmarks(frame)

    def _extract_landmarks(self, frame: np.ndarray) -> Optional[np.ndarray]:
        results = self.detector.process(cv2.cvtColor(frame, cv2.COLOR_BGR2RGB))
        if not results.multi_hand_landmarks:
            return None
        coords = np.array([[lm.x, lm.y, lm.z] for lm in results.multi_hand_landmarks[0].landmark])
        wrist = coords[0]
        centered = coords - wrist
        scale = np.linalg.norm(centered, axis=1).max() or 1.0
        normalized = centered / scale
        return np.expand_dims(normalized.flatten(), 0)

    def _predict(self, frame: np.ndarray) -> Optional[tuple[str, float]]:
        features = self._preprocess_frame(frame)
        if features is None:
            return None
        if self.cfg["modelType"] == "cnn-image":
            probs = self.model.predict(features, verbose=0)[0]
        else:
            probs = self.model.predict_proba(features)[0]
        idx = int(np.argmax(probs))
        return self.classes[idx], float(probs[idx])

    def _update_debounce(self, label: str) -> Optional[str]:
        self.debounce_buffer.append(label)
        maxlen = self.cfg["debounceFrames"]
        if len(self.debounce_buffer) > maxlen:
            self.debounce_buffer.pop(0)
        if len(self.debounce_buffer) == maxlen and len(set(self.debounce_buffer)) == 1:
            return label
        return None

    def recv(self, frame):  # type: ignore[override]
        img = frame.to_ndarray(format="bgr24")
        prediction = self._predict(img)
        label, conf = ("-", 0.0)
        stable_label = None
        if prediction:
            label, conf = prediction
            stable_label = self._update_debounce(label)
            self.last_prediction = (label, conf)
            if (
                self.autospeak
                and stable_label
                and conf >= self.cfg["minConfidence"]
                and stable_label != self.last_spoken
            ):
                speak_text(
                    stable_label,
                    provider=self.cfg["tts"]["provider"],
                    language=self.cfg["tts"]["language"],
                )
                self.last_spoken = stable_label

        overlay = img.copy()
        cv2.putText(overlay, f"Pred: {label} ({conf:.2f})", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
        if stable_label:
            cv2.putText(overlay, f"Stable: {stable_label}", (10, 70), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 0), 2)
        return av.VideoFrame.from_ndarray(overlay, format="bgr24")


def main():
    st.title("🖐️ Sign2Speech Demo")
    st.markdown("Deteksi gesture sederhana dan ubah jadi suara secara lokal.")

    cfg_path = Path("config.yaml")
    models_dir = Path("models")

    cfg, classes, model = load_models(cfg_path, models_dir)

    col1, col2 = st.columns([3, 2])

    with col1:
        st.subheader("Webcam Feed")
        autospeak = st.checkbox("Auto Speak stabilized label", value=True)

        webrtc_ctx = webrtc_streamer(
            key="sign2speech",
            mode="SENDRECV",
            video_frame_callback=None,
            media_stream_constraints={"video": True, "audio": False},
            rtc_configuration=RTCConfiguration({"iceServers": [{"urls": ["stun:stun.l.google.com:19302"]}]}),
            video_processor_factory=lambda: SignVideoProcessor(cfg, classes, model, autospeak),
        )

    with col2:
        st.subheader("Prediction")
        placeholder = st.empty()

        if webrtc_ctx and webrtc_ctx.video_processor:
            latest = webrtc_ctx.video_processor.last_prediction
            if latest:
                placeholder.metric("Prediksi", f"{latest[0]} ({latest[1]:.2f})")
            else:
                placeholder.info("Menunggu prediksi...")
        else:
            placeholder.info("Aktifkan kamera pada panel kiri.")

        st.subheader("Speak Manual")
        tts_provider = st.selectbox("Provider TTS", ["gtts", "pyttsx3"], index=0)
        manual_label = st.selectbox("Pilih label", classes)
        if st.button("Speak"):
            speak_text(manual_label, provider=tts_provider, language=cfg["tts"]["language"])
            st.success(f"Sudah disuarakan: {manual_label}")

        st.subheader("Quick Info")
        st.write(
            {
                "Model": cfg["modelType"],
                "Debounce": cfg["debounceFrames"],
                "MinConfidence": cfg["minConfidence"],
            }
        )

    with st.expander("Log"):
        st.caption("Gunakan tombol 'q' di window OpenCV untuk menghentikan script CLI realtime.")
        st.json(cfg)


if __name__ == "__main__":
    main()
