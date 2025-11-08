siap bro. ini **tech spec** ringkas tapi lengkap buat proyek **Sign-to-Speech (deteksi bahasa isyarat → suara)** yang nanti bisa kamu teruskan ke “codex” untuk implementasi.

# Tech Spec — Sign2Speech (Gesture → Teks → Voice)

## 1) Ringkasan

* **Tujuan:** mendeteksi gesture tangan (5–10 kelas) dari kamera, mengubahnya menjadi teks, lalu menyuarakannya (TTS) secara real-time.
* **Pendekatan model:** dua opsi implementasi (boleh pilih salah satu atau keduanya):

  * **A. CNN-Image**: klasifikasi gambar frame (from scratch, 128×128).
  * **B. Landmark-MLP**: ekstraksi 21 titik tangan via MediaPipe, klasifikasi MLP.
* **Demo target:** Streamlit app (webcam preview, prediksi live, tombol “Speak”).

## 2) Scope

* **In-scope:** 1 tangan, latar sederhana, 5–10 gesture tetap, real-time local.
* **Out-of-scope:** kalimat berurutan (sequence), 2 tangan bersamaan, multi-person, ASL full.

## 3) Definisi Kelas (contoh)

```
["halo", "iya", "tidak", "tolong", "terima_kasih", "baik"]
```

`label_map.json`:

```json
{"halo":0,"iya":1,"tidak":2,"tolong":3,"terima_kasih":4,"baik":5}
```

## 4) Arsitektur Sistem (tingkat tinggi)

Webcam → (opsional: deteksi tangan) → Preprocess → Model Klasifikasi → Post-process (debounce) → Text Output → TTS → Speaker
Komponen:

* `collector` (pengumpulan data), `preprocessor`, `trainer`, `evaluator`, `realtime-infer`, `tts`, `ui-streamlit`.

## 5) Lingkungan & Dependensi

* **Bahasa:** Python 3.10+
* **Lib utama:** `opencv-python`, `numpy`, `pandas`, `scikit-learn`, `tensorflow` (atau `torch`), `mediapipe` (opsional), `gTTS` atau `pyttsx3`, `matplotlib`, `streamlit`.
* **OS target dev:** macOS/Windows/Linux (CPU saja cukup).

## 6) Struktur Proyek

```
sign2speech/
  data/
    raw/<class>/*.jpg|*.png
    processed/<class>/*.npy|*.jpg
  models/
    cnn_model.h5
    mlp_model.pkl
  reports/
    training_curves.png
    confusion_matrix.png
    metrics.json
  src/
    collect.py
    preprocess.py
    train.py
    evaluate.py
    infer_realtime.py
    tts.py
    utils/
      io.py logger.py viz.py
  app.py                 # streamlit ui
  assets/label_map.json
  config.yaml
  requirements.txt
  README.md
```

## 7) Dataset & Prosedur Data

* **Target:** ≥100 gambar/kelas (lebih baik 150–200), variatif sudut/pencahayaan.
* **Collector:** `collect.py` (OpenCV) → simpan frame tiap 300–500 ms, tombol hotkey per kelas.
* **Split:** train/val/test = 70/15/15 (stratified).
* **Preprocess:**

  * **CNN-Image:** resize 128×128, normalize [0,1], augment ringan (flip, brightness, rotation kecil).
  * **Landmark-MLP:** ekstrak 21 keypoints (x,y,z) → normalisasi relatif wrist & skala, flatten (63 dim).

## 8) Spesifikasi Model

### Opsi A — CNN (Keras)

* **Input:** (128,128,1) atau (128,128,3)
* **Arsitektur (baseline):**

  * [Conv(32,3)→BN→ReLU→MaxPool] × 1
  * [Conv(64,3)→BN→ReLU→MaxPool] × 1
  * [Conv(128,3)→BN→ReLU→GlobalAvgPool]
  * Dense(128)→ReLU→Dropout(0.3)→Dense(numClasses, Softmax)
* **Training:** Adam(lr=1e-3), CE loss, epochs 20–30, EarlyStopping(patience=5), ReduceLROnPlateau.
* **Target latency:** ≤ 25 ms/frame (CPU) untuk 128×128.

### Opsi B — Landmark → MLP (sklearn / Keras)

* **Fitur:** vektor 63 dim (x,y,z * 21 landmarks), dist-normalized.
* **Model:**

  * `MLPClassifier(hidden_layer_sizes=[128,64], activation='relu', alpha=1e-4)` **atau**
  * Keras Dense(128→64→classes).
* **Kelebihan:** robust terhadap latar, lebih ringan realtime.

## 9) Evaluasi & Pelaporan

* **Metrik:** accuracy (macro), precision/recall/F1 per kelas, confusion matrix.
* **Artefak:** `metrics.json`, `training_curves.png`, `confusion_matrix.png`.
* **Uji realtime:** stabilitas label (debounce 5 frame), FPS, latency.

## 10) Realtime Inference Pipeline

* **Langkah:**

  1. Capture frame (OpenCV).
  2. **CNN-Image:** resize→normalize→predict.
     **Landmark-MLP:** MediaPipe Hands→keypoints→normalize→predict.
  3. Debounce: butuhkan N frame berturut-turut (default 5) untuk “fix” label.
  4. Render UI: label & confidence; kirim ke TTS jika label fix.
* **Konfigurasi (config.yaml):**

```yaml
modelType: "landmark-mlp"   # or "cnn-image"
classes: ["halo","iya","tidak","tolong","terima_kasih","baik"]
debounceFrames: 5
minConfidence: 0.70
tts:
  provider: "gtts"          # or "pyttsx3"
  language: "id"
video:
  width: 640
  height: 480
  deviceIndex: 0
```

## 11) UI (Streamlit) — Kebutuhan

* Panel kiri: video feed (frame ditampilkan per ~50–100ms).
* Panel kanan: label prediksi + skor, tombol “Speak”, selector provider TTS, selector kelas untuk uji manual.
* Log singkat: FPS, latency, status model loaded.

## 12) API/CLI Sederhana (opsional)

* **CLI**:

  * `python src/collect.py --class halo --count 150`
  * `python src/preprocess.py --mode landmark|cnn`
  * `python src/train.py --model landmark-mlp --epochs 30`
  * `python src/evaluate.py --model landmark-mlp`
  * `python src/infer_realtime.py --config config.yaml`
* **Local API (opsional FastAPI)**:

  * `POST /predict` → body: image base64 / landmark array → `{label, confidence}`
  * `POST /speak` → body: `{text, lang}` → audio stream/file path

### Contoh JSON (Predict - landmark):

```json
{
  "landmarks": [0.12,0.33,0.01, ... 63 dim ...]
}
```

**Response:**

```json
{
  "label": "halo",
  "confidence": 0.91,
  "probs": {"halo":0.91,"iya":0.02,"tidak":0.01,"tolong":0.03,"terima_kasih":0.02,"baik":0.01}
}
```

## 13) Logging, Metrics, & Persistensi

* **Model:** `models/<modelName>.{h5|pkl}`
* **Run logs:** `reports/metrics.json`, `training_curves.png`, `confusion_matrix.png`
* **Ablation (opsional):** simpan hyperparams & skor ke `reports/experiments.csv`

## 14) Keamanan, Etika, & Privasi

* Simpan data lokal; jika rekam orang lain → minta persetujuan.
* Hindari upload dataset ke cloud publik tanpa izin.
* Sertakan **Dataset Card** (sumber, jumlah, variasi, keterbatasan).

## 15) Risiko & Mitigasi

* **Overfitting dataset kecil:** augmentasi/regularisasi, early stopping.
* **Latar bising:** pakai opsi Landmark, atau minta latar polos di demo.
* **Gesture mirip (confusable):** tambahkan sampel sulit & aturan debounce lebih ketat.

## 16) Timeline Implementasi (5 hari efektif)

1. Scope final + collector + ambil 2 kelas → validasi pipeline data
2. Selesai ambil semua kelas + preprocess + split
3. Train Landmark-MLP + evaluasi + plot
4. Realtime + TTS + Streamlit
5. (opsional) CNN baseline + dokumentasi & slide

## 17) Definition of Done (DoD)

* Akurasi val ≥ 85% untuk ≥5 kelas.
* Artefak lengkap: curves, confusion matrix, metrics.json.
* Aplikasi realtime berjalan (prediksi stabil, suara keluar).
* README & slide presentasi (8–12 halaman).

## 18) Konfigurasi & DTO (untuk “codex”)

**Config DTO:**

```ts
export interface Sign2SpeechConfig {
  modelType: "cnn-image" | "landmark-mlp";
  classes: string[];
  debounceFrames: number;
  minConfidence: number;
  tts: { provider: "gtts" | "pyttsx3"; language: string };
  video: { width: number; height: number; deviceIndex: number };
}
```

**Predict DTO (Landmark):**

```ts
export interface PredictRequest {
  landmarks: number[]; // length 63
}
export interface PredictResponse {
  label: string;
  confidence: number;
  probs: Record<string, number>;
}
```

**Metrics DTO:**

```ts
export interface TrainMetrics {
  overall: { accuracy: number; macroF1: number };
  perClass: Record<string, { precision: number; recall: number; f1: number }>;
  confusionMatrix: number[][];
  bestEpoch: number;
}
```

## 19) Parameter Default (mulai aman)

* `imgSize=128`, `batchSize=32`, `epochs=30`, `lr=1e-3`
* `debounceFrames=5`, `minConfidence=0.70`

---

kalau cocok, tinggal bilang **pilih opsi model A (cnn-image) atau B (landmark-mlp)** — nanti gue terusin ke **template file kosong + stub kode** sesuai struktur di atas (biar “codex” tinggal isi detail implementasinya).
