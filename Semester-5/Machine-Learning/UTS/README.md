# 🌸 Proyek Klasifikasi Iris - UTS Machine Learning

Proyek klasifikasi untuk memprediksi jenis bunga Iris menggunakan machine learning.

## 📋 Deskripsi Proyek

Proyek ini merupakan implementasi sistem klasifikasi untuk memprediksi spesies bunga Iris (Setosa, Versicolor, atau Virginica) berdasarkan karakteristik fisiknya menggunakan 4 algoritma machine learning:

1. **Logistic Regression**
2. **Decision Tree**
3. **K-Nearest Neighbors (KNN)**
4. **Support Vector Machine (SVM)**

## 📊 Dataset

**Iris Dataset** - Dataset klasik yang berisi 150 sampel dengan:
- 4 fitur numerik (Sepal Length, Sepal Width, Petal Length, Petal Width)
- 3 kelas target (Setosa, Versicolor, Virginica)
- Balanced dataset (50 sampel per kelas)

## 🚀 Quick Start

### Prerequisites

Pastikan Python 3.8+ sudah terinstall di sistem Anda.

### Installation & Setup

```bash
# 1. Pindah ke folder project
cd /Users/rafli/Documents/Kuliah/Unpam/Semester-5/Machine-Learning/UTS

# 2. Install dependencies
pip3 install -r requirements.txt

# 3. Generate dataset CSV
python3 export_dataset.py
```

### Menjalankan Analisis (Pilih Salah Satu)

#### Opsi A: Jupyter Notebook (REKOMENDASI ⭐)
```bash
jupyter notebook klasifikasi_iris.ipynb
# Kemudian: Cell → Run All
```

#### Opsi B: Python Script
```bash
python3 klasifikasi_iris.py
```

## 📁 Struktur File

```
UTS/
│
├── 📊 DATASET
│   └── dataset_iris.csv                    # Dataset (run export_dataset.py)
│
├── 🤖 MODEL (Generated setelah run)
│   ├── best_model_*.pkl                    # Model terbaik
│   ├── scaler.pkl                          # StandardScaler
│   └── model_comparison_results.csv        # Hasil evaluasi
│
├── 💻 CODING
│   ├── klasifikasi_iris.ipynb              # ⭐ Notebook UTAMA
│   ├── klasifikasi_iris.py                 # Python script alternatif
│   └── export_dataset.py                   # Generate dataset CSV
│
├── 📄 LAPORAN & DOKUMENTASI
│   ├── LAPORAN_UTS_MACHINE_LEARNING.md     # ⭐ Laporan lengkap
│   ├── KARTU_UJIAN_UTS.md                  # ⭐ Kartu ujian
│   ├── README.md                           # File ini
│   ├── STRUKTUR_PROJECT.md                 # Panduan struktur
│   └── requirements.txt                    # Dependencies
│
└── 🖼️ VISUALISASI (Generated setelah run)
    ├── distribusi_kelas.png
    ├── distribusi_fitur.png
    ├── boxplot_fitur.png
    ├── correlation_matrix.png
    ├── pairplot.png
    ├── confusion_matrices.png
    ├── roc_curves.png
    ├── model_comparison.png
    ├── radar_comparison.png
    └── cross_validation.png
```

## 🔍 Isi Notebook

### 1. Import Library
Import semua library yang diperlukan (NumPy, Pandas, Scikit-learn, dll.)

### 2. Load dan Eksplorasi Data
- Load Iris dataset
- Tampilkan informasi dasar
- Statistik deskriptif
- Cek missing values
- Distribusi kelas

### 3. Exploratory Data Analysis (EDA)
- Visualisasi distribusi kelas
- Histogram fitur per spesies
- Box plot untuk deteksi outliers
- Correlation matrix
- Pair plot

### 4. Data Preprocessing
- Split data (80% train, 20% test)
- Standardisasi fitur dengan StandardScaler
- Stratified sampling

### 5. Model Training
Training 4 algoritma klasifikasi:
- Logistic Regression
- Decision Tree (max_depth=5)
- K-Nearest Neighbors (n_neighbors=5)
- Support Vector Machine (kernel='rbf')

### 6. Model Evaluation
- Accuracy, Precision, Recall, F1-Score
- Confusion Matrix untuk semua model
- ROC Curve dan AUC Score
- Classification Report per kelas

### 7. Perbandingan Model
- Tabel perbandingan performa
- Bar chart untuk setiap metrik
- Radar chart perbandingan

### 8. Cross-Validation
- 5-Fold Cross-Validation
- Visualisasi hasil CV

### 9. Kesimpulan
- Summary hasil evaluasi
- Model terbaik
- Insight dan rekomendasi

### 10. Simpan Model
- Export hasil ke CSV
- Simpan model terbaik dengan pickle
- Simpan scaler

## 📈 Hasil Performa

Semua model menunjukkan performa excellent (>95% accuracy):

| Model | Accuracy | Precision | Recall | F1-Score |
|-------|----------|-----------|---------|----------|
| Logistic Regression | ~100% | ~1.00 | ~1.00 | ~1.00 |
| Decision Tree | ~100% | ~1.00 | ~1.00 | ~1.00 |
| K-Nearest Neighbors | ~100% | ~1.00 | ~1.00 | ~1.00 |
| Support Vector Machine | ~100% | ~1.00 | ~1.00 | ~1.00 |

*Catatan: Hasil actual dapat sedikit bervariasi tergantung random state*

## 🎯 Cara Menggunakan Model Tersimpan

Setelah menjalankan notebook, Anda dapat menggunakan model tersimpan:

```python
import pickle
import numpy as np

# Load model dan scaler
with open('best_model_support_vector_machine.pkl', 'rb') as f:
    model = pickle.load(f)

with open('scaler.pkl', 'rb') as f:
    scaler = pickle.load(f)

# Prediksi data baru
# Format: [Sepal Length, Sepal Width, Petal Length, Petal Width]
new_flower = np.array([[5.1, 3.5, 1.4, 0.2]])

# Scaling dan prediksi
new_flower_scaled = scaler.transform(new_flower)
prediction = model.predict(new_flower_scaled)
probability = model.predict_proba(new_flower_scaled)

# Mapping hasil
species_map = {0: 'Setosa', 1: 'Versicolor', 2: 'Virginica'}
print(f"Predicted Species: {species_map[prediction[0]]}")
print(f"Probabilities: {probability[0]}")
```

## 📚 Dependencies

- **NumPy** - Numerical computing
- **Pandas** - Data manipulation
- **Matplotlib** - Data visualization
- **Seaborn** - Statistical data visualization
- **Scikit-learn** - Machine learning algorithms
- **Jupyter** - Interactive notebook

Lihat `requirements.txt` untuk versi lengkap.

## ✅ Checklist Tugas UTS

### Komponen Utama (WAJIB):
- [x] **1. Dataset** → `dataset_iris.csv`
- [x] **2. Model** → `best_model_*.pkl`, `scaler.pkl`
- [x] **3. Coding** → `klasifikasi_iris.ipynb` (Notebook) atau `klasifikasi_iris.py`
- [x] **4. Laporan** → `LAPORAN_UTS_MACHINE_LEARNING.md`
- [x] **5. Kartu Ujian** → `KARTU_UJIAN_UTS.md` (isi identitas Anda!)

### Requirements Teknis:
- [x] Dataset dengan variabel target kategorikal
- [x] EDA dan preprocessing lengkap
- [x] Minimal 2 algoritma klasifikasi (tersedia 4: LR, DT, KNN, SVM)
- [x] Evaluasi dengan Confusion Matrix
- [x] Evaluasi dengan Accuracy, Precision, Recall, F1-score
- [x] ROC Curve dan AUC
- [x] Perbandingan hasil antar model
- [x] Cross-validation
- [x] Kesimpulan dan rekomendasi

## 👨‍💻 Author

**[Nama Mahasiswa]**  
NIM: [NIM Mahasiswa]  
Universitas Pamulang - Semester 5  
Mata Kuliah: Machine Learning

## 📄 License

Proyek ini dibuat untuk keperluan akademis (UTS Machine Learning).

## 🙏 Acknowledgments

- Dataset: UCI Machine Learning Repository
- Library: Scikit-learn team
- Inspiration: Ronald Fisher's original Iris paper (1936)

---

**Tanggal:** 31 Oktober 2025  
**Status:** ✅ Complete

