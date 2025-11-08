# Sign2Speech

Pipeline lengkap untuk mendeteksi gesture tangan (5–10 kelas) dan menyuarakannya secara realtime.

## Fitur
- Dua opsi model: CNN gambar 128×128 atau MLP berbasis landmark MediaPipe.
- Skrip CLI terpisah untuk koleksi data, preprocessing, training, evaluasi, dan inferensi realtime.
- Aplikasi Streamlit menampilkan webcam feed, prediksi live, log sederhana, dan tombol Speak.
- Modul TTS fleksibel (gTTS / pyttsx3).

## Persiapan Lingkungan
```bash
python -m venv .venv
source .venv/bin/activate  # atau .venv\Scripts\activate di Windows
pip install -r requirements.txt
```
Beberapa paket (TensorFlow, mediapipe, pyttsx3) cukup besar — pastikan pip berhasil sebelum menjalankan pipeline.

## Struktur Direktori
```
sign2speech/
  data/raw/           # hasil capture per kelas (jpg)
  data/processed/     # dataset .npy hasil preprocess
  models/             # model tersimpan (.h5 / .pkl)
  reports/            # metrics.json, plot evaluasi
  assets/label_map.json
  config.yaml
  src/collect.py ... infer_realtime.py ...
  app.py
```

## Alur Kerja
1. **Siapkan kelas** sesuai `assets/label_map.json`. Ubah file ini bila menambah kelas.
2. **Koleksi data** per kelas:
   ```bash
   python src/collect.py halo 150 --auto
   ```
3. **Preprocess** sesuai model target:
   ```bash
   python src/preprocess.py --mode cnn
   # atau
   python src/preprocess.py --mode landmark
   ```
   Output `data/processed/{cnn|landmark}_dataset.npy` berisi `X` dan `y`.
4. **Training**:
   ```bash
   python src/train.py --model cnn-image --dataset data/processed/cnn_dataset.npy
   python src/train.py --model landmark-mlp --dataset data/processed/landmark_dataset.npy
   ```
5. **Evaluasi** opsional di set terpisah:
   ```bash
   python src/evaluate.py --model landmark-mlp --dataset data/processed/landmark_dataset.npy
   ```
6. **Realtime CLI** (OpenCV window, tekan `q` untuk keluar):
   ```bash
   python src/infer_realtime.py --config config.yaml --autospeak
   ```
7. **Streamlit UI** (webcam + tombol Speak):
   ```bash
   streamlit run app.py
   ```

## Dataset Placeholder
Repo ini **tidak menyertakan dataset**. Taruh gambar mentah ke `data/raw/<nama_kelas>/*.jpg`. Minimal 100 gambar per kelas, variasikan sudut, jarak, pencahayaan. Pastikan hanya 1 tangan terlihat.

Untuk opsi landmark, mediapipe secara otomatis mengekstrak 21 titik tangan. Pastikan tangan jelas agar landmark terdeteksi.

## Konfigurasi
Atur parameter global di `config.yaml`:
- `modelType`: `cnn-image` atau `landmark-mlp`.
- `classes`: urutan label.
- `debounceFrames`, `minConfidence`: kestabilan prediksi realtime.
- `tts`: provider (`gtts`/`pyttsx3`) & bahasa.
- `video`: resolusi webcam.

## Catatan Tambahan
- Simpan artefak training di `reports/` (`metrics.json`, `training_curves.png`, dll).
- Tambahkan dokumentasi dataset (jumlah sampel, kondisi, keterbatasan) sebelum publikasi.
- Jika hanya ingin inference cepat tanpa Streamlit, gunakan `infer_realtime.py` dengan flag `--autospeak`.
