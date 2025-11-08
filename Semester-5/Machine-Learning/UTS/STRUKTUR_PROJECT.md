# 📁 STRUKTUR PROJECT UTS MACHINE LEARNING

## ✅ KELENGKAPAN PROJECT

### 1. 📊 DATASET
**File:** `dataset_iris.csv`
- Dataset akan dibuat otomatis saat menjalankan script
- Berisi 150 baris data dengan 4 fitur dan 3 kelas
- **Cara generate:**
  ```bash
  python3 export_dataset.py
  ```

### 2. 🤖 MODEL (File Model yang Sudah Diolah)
**File Model Tersimpan:**
- `best_model_*.pkl` - Model terbaik hasil training
- `scaler.pkl` - StandardScaler untuk preprocessing
- `model_comparison_results.csv` - Hasil perbandingan semua model

**Cara Generate:**
- Model akan otomatis tersimpan saat menjalankan notebook atau script Python

### 3. 💻 CODING (Python Script)
**File Coding:**

#### a. Jupyter Notebook (REKOMENDASI)
- **File:** `klasifikasi_iris.ipynb`
- Lengkap dengan output dan visualisasi
- Interactive dan mudah dipahami
- **Cara menjalankan:**
  ```bash
  jupyter notebook klasifikasi_iris.ipynb
  ```

#### b. Python Script (Alternatif)
- **File:** `klasifikasi_iris.py`
- Versi script dari notebook
- Dapat dijalankan langsung tanpa Jupyter
- **Cara menjalankan:**
  ```bash
  python3 klasifikasi_iris.py
  ```

#### c. Helper Scripts
- **File:** `export_dataset.py` - Export dataset ke CSV
- **File:** `setup_and_run.sh` - Setup otomatis (opsional)

### 4. 📄 LAPORAN
**File Laporan:**
- **File:** `LAPORAN_UTS_MACHINE_LEARNING.md`
- Lengkap dengan 7 bab
- Maksimal 3 halaman (dapat di-print)
- Mencakup:
  1. Deskripsi Dataset
  2. Model yang Digunakan
  3. Preprocessing Data
  4. Hasil Evaluasi dan Pembahasan
  5. Pembahasan Detail
  6. Kesimpulan dan Rekomendasi
  7. Referensi

### 5. 📋 KARTU UJIAN
**File:** `KARTU_UJIAN_UTS.md`
- Template kartu ujian untuk diisi
- Checklist kelengkapan project
- Rubrik penilaian detail
- Form persetujuan mahasiswa dan dosen

---

## 📂 STRUKTUR FOLDER LENGKAP

```
UTS/
│
├── 📊 DATASET
│   └── dataset_iris.csv                    # Dataset (generated)
│
├── 🤖 MODEL  
│   ├── best_model_*.pkl                    # Model terbaik (generated)
│   ├── scaler.pkl                          # Scaler (generated)
│   └── model_comparison_results.csv        # Hasil evaluasi (generated)
│
├── 💻 CODING
│   ├── klasifikasi_iris.ipynb              # Notebook UTAMA ⭐
│   ├── klasifikasi_iris.py                 # Python script alternatif
│   └── export_dataset.py                   # Helper script
│
├── 📄 LAPORAN
│   ├── LAPORAN_UTS_MACHINE_LEARNING.md     # Laporan UTAMA ⭐
│   └── KARTU_UJIAN_UTS.md                  # Kartu ujian ⭐
│
├── 📚 DOKUMENTASI
│   ├── README.md                           # Petunjuk lengkap
│   ├── STRUKTUR_PROJECT.md                 # File ini
│   └── requirements.txt                    # Dependencies
│
├── 🖼️ VISUALISASI (Generated)
│   ├── distribusi_kelas.png
│   ├── distribusi_fitur.png
│   ├── boxplot_fitur.png
│   ├── correlation_matrix.png
│   ├── pairplot.png
│   ├── confusion_matrices.png
│   ├── roc_curves.png
│   ├── model_comparison.png
│   ├── radar_comparison.png
│   └── cross_validation.png
│
└── 🛠️ SETUP (Opsional)
    └── setup_and_run.sh                    # Script setup otomatis
```

---

## 🚀 CARA MENGGUNAKAN

### Step 1: Install Dependencies
```bash
cd /Users/rafli/Documents/Kuliah/Unpam/Semester-5/Machine-Learning/UTS
pip3 install -r requirements.txt
```

### Step 2: Generate Dataset
```bash
python3 export_dataset.py
```
**Output:** `dataset_iris.csv`

### Step 3: Jalankan Analisis (PILIH SALAH SATU)

#### Opsi A: Menggunakan Jupyter Notebook (REKOMENDASI)
```bash
jupyter notebook klasifikasi_iris.ipynb
```
Kemudian jalankan semua cell (Cell → Run All)

#### Opsi B: Menggunakan Python Script
```bash
python3 klasifikasi_iris.py
```

### Step 4: Cek File yang Dihasilkan
Setelah menjalankan, akan muncul:
- ✅ `best_model_*.pkl` - Model tersimpan
- ✅ `scaler.pkl` - Scaler tersimpan
- ✅ `model_comparison_results.csv` - Hasil evaluasi
- ✅ 10 file visualisasi (.png)

### Step 5: Lengkapi Kartu Ujian
- Buka `KARTU_UJIAN_UTS.md`
- Isi identitas mahasiswa
- Centang checklist kelengkapan
- Print dan tanda tangan

---

## 📦 CHECKLIST SEBELUM SUBMIT

### ✅ Kelengkapan File
- [ ] `dataset_iris.csv` tersedia
- [ ] `klasifikasi_iris.ipynb` sudah dijalankan dengan output
- [ ] `best_model_*.pkl` dan `scaler.pkl` tersimpan
- [ ] `LAPORAN_UTS_MACHINE_LEARNING.md` sudah dibaca
- [ ] `KARTU_UJIAN_UTS.md` sudah diisi dan ditandatangani
- [ ] Semua visualisasi (.png) tersimpan
- [ ] `model_comparison_results.csv` tersedia

### ✅ Kualitas Code
- [ ] Semua cell di notebook dapat dijalankan tanpa error
- [ ] Output visualisasi muncul dengan benar
- [ ] Model accuracy > 90%
- [ ] Tidak ada missing values atau error

### ✅ Dokumentasi
- [ ] README.md sudah dibaca
- [ ] Laporan maksimal 3 halaman
- [ ] Kartu ujian sudah diisi lengkap
- [ ] Nama dan NIM sudah diisi di semua dokumen

---

## 📤 FORMAT PENGUMPULAN

### Opsi 1: Folder ZIP
```
UTS_MachineLearning_[NIM]_[Nama].zip
│
└── Berisi semua file di atas
```

### Opsi 2: Google Drive/OneDrive
1. Upload semua file ke folder cloud
2. Set permission: "Anyone with the link can view"
3. Submit link di LMS

### Opsi 3: GitHub Repository (Bonus)
1. Create repository: `UTS-ML-Iris-Classification`
2. Push semua file
3. Submit link repository

---

## 💡 TIPS SUKSES

### 1. Jalankan Notebook dengan Benar
- Pastikan jalankan cell secara berurutan
- Restart kernel jika ada error: `Kernel → Restart & Run All`
- Cek semua visualisasi muncul

### 2. Baca Laporan dengan Teliti
- Pahami setiap section
- Sesuaikan dengan hasil yang didapat
- Catat insight yang menarik

### 3. Presentasi (Jika Diperlukan)
- Siapkan penjelasan untuk setiap model
- Pahami mengapa model tertentu lebih baik
- Siap menjawab pertanyaan tentang preprocessing dan evaluation

### 4. Backup
- Simpan backup di 2 tempat berbeda
- Test buka file sebelum submit
- Pastikan file tidak corrupt

---

## ❓ TROUBLESHOOTING

### Error: Module not found
**Solusi:**
```bash
pip3 install -r requirements.txt
```

### Error: Jupyter not found
**Solusi:**
```bash
pip3 install jupyter notebook
```

### Visualisasi tidak muncul
**Solusi:**
- Pastikan matplotlib terinstall
- Restart kernel di Jupyter
- Jalankan ulang cell visualisasi

### Model accuracy rendah
**Solusi:**
- Check data preprocessing
- Pastikan scaling dilakukan dengan benar
- Cek train-test split

---

## 📞 KONTAK

Jika ada pertanyaan:
1. Hubungi dosen pengampu
2. Diskusi dengan teman sekelas
3. Konsultasi saat jam praktikum

---

## ⭐ NILAI TAMBAH

Untuk mendapat nilai maksimal:
1. ✅ Code rapi dan terorganisir
2. ✅ Visualisasi informatif dan menarik
3. ✅ Laporan detail dan insightful
4. ✅ Semua algoritma diimplementasi dengan benar
5. ✅ Cross-validation dilakukan
6. ✅ Kesimpulan mendalam dan data-driven

---

**SELAMAT MENGERJAKAN!** 🎓

Good luck dengan UTS Machine Learning Anda!

