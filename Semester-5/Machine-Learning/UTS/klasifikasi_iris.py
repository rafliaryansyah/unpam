"""
Proyek Klasifikasi: Prediksi Jenis Bunga Iris
UTS Machine Learning - Universitas Pamulang

Versi Python Script (.py) sebagai alternatif dari Jupyter Notebook
Untuk menjalankan: python klasifikasi_iris.py
"""

# ============================================================================
# 1. IMPORT LIBRARY
# ============================================================================

import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import warnings
import pickle
from math import pi

warnings.filterwarnings('ignore')

# Library sklearn
from sklearn.datasets import load_iris
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.preprocessing import StandardScaler, label_binarize

# Model Klasifikasi
from sklearn.linear_model import LogisticRegression
from sklearn.tree import DecisionTreeClassifier
from sklearn.neighbors import KNeighborsClassifier
from sklearn.svm import SVC

# Evaluasi Model
from sklearn.metrics import (
    confusion_matrix, 
    classification_report, 
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    roc_curve,
    roc_auc_score
)

# Set style
plt.style.use('seaborn-v0_8-darkgrid')
sns.set_palette('husl')

print("="*70)
print("PROYEK KLASIFIKASI IRIS - UTS MACHINE LEARNING")
print("Universitas Pamulang")
print("="*70)
print("\n✓ Library berhasil diimport\n")

# ============================================================================
# 2. LOAD DAN EKSPLORASI DATA
# ============================================================================

print("="*70)
print("2. LOAD DAN EKSPLORASI DATA")
print("="*70)

# Load dataset Iris
iris = load_iris()
df = pd.DataFrame(data=iris.data, columns=iris.feature_names)
df['target'] = iris.target
df['species'] = df['target'].map({0: 'setosa', 1: 'versicolor', 2: 'virginica'})

print("Dataset Iris berhasil dimuat!")
print(f"\nJumlah sampel: {df.shape[0]}")
print(f"Jumlah fitur: {df.shape[1]-2}")
print(f"Kelas target: {df['species'].unique()}\n")

# Statistik deskriptif
print("\nStatistik Deskriptif:")
print(df.describe())

# Cek missing values
print(f"\nMissing Values: {df.isnull().sum().sum()}")

# Distribusi kelas
print("\nDistribusi Kelas Target:")
print(df['species'].value_counts())

# ============================================================================
# 3. EXPLORATORY DATA ANALYSIS (EDA)
# ============================================================================

print("\n" + "="*70)
print("3. EXPLORATORY DATA ANALYSIS")
print("="*70)

# Visualisasi distribusi kelas
fig, axes = plt.subplots(1, 2, figsize=(14, 5))

df['species'].value_counts().plot(kind='bar', ax=axes[0], color=['#FF6B6B', '#4ECDC4', '#45B7D1'])
axes[0].set_title('Distribusi Kelas Target', fontsize=14, fontweight='bold')
axes[0].set_xlabel('Spesies')
axes[0].set_ylabel('Jumlah')

df['species'].value_counts().plot(kind='pie', ax=axes[1], autopct='%1.1f%%', 
                                   colors=['#FF6B6B', '#4ECDC4', '#45B7D1'])
axes[1].set_title('Proporsi Kelas Target', fontsize=14, fontweight='bold')
axes[1].set_ylabel('')

plt.tight_layout()
plt.savefig('distribusi_kelas.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Visualisasi distribusi kelas tersimpan")

# Correlation Matrix
plt.figure(figsize=(10, 8))
correlation_matrix = df[iris.feature_names].corr()
sns.heatmap(correlation_matrix, annot=True, cmap='coolwarm', center=0, 
            square=True, linewidths=1, fmt='.2f')
plt.title('Correlation Matrix - Fitur Iris', fontsize=14, fontweight='bold', pad=20)
plt.tight_layout()
plt.savefig('correlation_matrix.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Correlation matrix tersimpan")

# Pair plot
pairplot = sns.pairplot(df, hue='species', markers=['o', 's', 'D'], 
                        palette='husl', diag_kind='kde', height=2.5)
pairplot.fig.suptitle('Pair Plot - Iris Dataset', y=1.02, fontsize=16, fontweight='bold')
plt.savefig('pairplot.png', dpi=300, bbox_inches='tight')
plt.close()
print("✓ Pair plot tersimpan")

# ============================================================================
# 4. DATA PREPROCESSING
# ============================================================================

print("\n" + "="*70)
print("4. DATA PREPROCESSING")
print("="*70)

# Pisahkan fitur dan target
X = df[iris.feature_names]
y = df['target']

print(f"Shape fitur (X): {X.shape}")
print(f"Shape target (y): {y.shape}")

# Split data
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42, stratify=y
)

print(f"\nTraining set: {X_train.shape[0]} sampel")
print(f"Testing set: {X_test.shape[0]} sampel")

# Standardisasi fitur
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

print("✓ Data berhasil distandardisasi")

# ============================================================================
# 5. MODEL TRAINING
# ============================================================================

print("\n" + "="*70)
print("5. MODEL TRAINING")
print("="*70)

# Train models
print("\nTraining Logistic Regression...")
lr_model = LogisticRegression(max_iter=200, random_state=42)
lr_model.fit(X_train_scaled, y_train)
y_pred_lr = lr_model.predict(X_test_scaled)
y_pred_proba_lr = lr_model.predict_proba(X_test_scaled)
print("✓ Logistic Regression selesai")

print("Training Decision Tree...")
dt_model = DecisionTreeClassifier(max_depth=5, random_state=42)
dt_model.fit(X_train_scaled, y_train)
y_pred_dt = dt_model.predict(X_test_scaled)
y_pred_proba_dt = dt_model.predict_proba(X_test_scaled)
print("✓ Decision Tree selesai")

print("Training K-Nearest Neighbors...")
knn_model = KNeighborsClassifier(n_neighbors=5)
knn_model.fit(X_train_scaled, y_train)
y_pred_knn = knn_model.predict(X_test_scaled)
y_pred_proba_knn = knn_model.predict_proba(X_test_scaled)
print("✓ K-Nearest Neighbors selesai")

print("Training Support Vector Machine...")
svm_model = SVC(kernel='rbf', probability=True, random_state=42)
svm_model.fit(X_train_scaled, y_train)
y_pred_svm = svm_model.predict(X_test_scaled)
y_pred_proba_svm = svm_model.predict_proba(X_test_scaled)
print("✓ Support Vector Machine selesai")

# ============================================================================
# 6. MODEL EVALUATION
# ============================================================================

print("\n" + "="*70)
print("6. MODEL EVALUATION")
print("="*70)

def evaluate_model(y_true, y_pred, model_name):
    """Fungsi untuk evaluasi model"""
    print(f"\n{'='*60}")
    print(f"EVALUASI MODEL: {model_name}")
    print(f"{'='*60}")
    
    accuracy = accuracy_score(y_true, y_pred)
    precision = precision_score(y_true, y_pred, average='weighted')
    recall = recall_score(y_true, y_pred, average='weighted')
    f1 = f1_score(y_true, y_pred, average='weighted')
    
    print(f"\n1. Accuracy: {accuracy:.4f} ({accuracy*100:.2f}%)")
    print(f"2. Precision: {precision:.4f}")
    print(f"3. Recall: {recall:.4f}")
    print(f"4. F1-Score: {f1:.4f}")
    
    return {
        'accuracy': accuracy,
        'precision': precision,
        'recall': recall,
        'f1_score': f1
    }

# Evaluasi semua model
results = {}
results['Logistic Regression'] = evaluate_model(y_test, y_pred_lr, 'Logistic Regression')
results['Decision Tree'] = evaluate_model(y_test, y_pred_dt, 'Decision Tree')
results['K-Nearest Neighbors'] = evaluate_model(y_test, y_pred_knn, 'K-Nearest Neighbors')
results['Support Vector Machine'] = evaluate_model(y_test, y_pred_svm, 'Support Vector Machine')

# Confusion Matrix
fig, axes = plt.subplots(2, 2, figsize=(16, 14))
models = [
    ('Logistic Regression', y_pred_lr),
    ('Decision Tree', y_pred_dt),
    ('K-Nearest Neighbors', y_pred_knn),
    ('Support Vector Machine', y_pred_svm)
]

for idx, (name, y_pred) in enumerate(models):
    row, col = idx // 2, idx % 2
    cm = confusion_matrix(y_test, y_pred)
    
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', 
                xticklabels=['Setosa', 'Versicolor', 'Virginica'],
                yticklabels=['Setosa', 'Versicolor', 'Virginica'],
                ax=axes[row, col], cbar_kws={'label': 'Count'})
    
    axes[row, col].set_title(f'Confusion Matrix: {name}', fontsize=12, fontweight='bold')
    axes[row, col].set_ylabel('True Label')
    axes[row, col].set_xlabel('Predicted Label')

plt.tight_layout()
plt.savefig('confusion_matrices.png', dpi=300, bbox_inches='tight')
plt.close()
print("\n✓ Confusion matrices tersimpan")

# ============================================================================
# 7. PERBANDINGAN MODEL
# ============================================================================

print("\n" + "="*70)
print("7. PERBANDINGAN MODEL")
print("="*70)

comparison_df = pd.DataFrame(results).T
comparison_df = comparison_df.round(4)

print("\nPERBANDINGAN PERFORMA MODEL:")
print(comparison_df)

# Simpan hasil
comparison_df.to_csv('model_comparison_results.csv')
print("\n✓ Hasil perbandingan disimpan ke 'model_comparison_results.csv'")

# ============================================================================
# 8. CROSS-VALIDATION
# ============================================================================

print("\n" + "="*70)
print("8. CROSS-VALIDATION")
print("="*70)

models_cv = {
    'Logistic Regression': lr_model,
    'Decision Tree': dt_model,
    'K-Nearest Neighbors': knn_model,
    'Support Vector Machine': svm_model
}

print("\n5-Fold Cross-Validation Results:")
for name, model in models_cv.items():
    scores = cross_val_score(model, X_train_scaled, y_train, cv=5, scoring='accuracy')
    print(f"{name}: {scores.mean():.4f} (+/- {scores.std():.4f})")

# ============================================================================
# 9. KESIMPULAN
# ============================================================================

print("\n" + "="*70)
print("9. KESIMPULAN")
print("="*70)

best_model_name = comparison_df['accuracy'].idxmax()
best_accuracy = comparison_df['accuracy'].max()

print(f"\nMODEL TERBAIK: {best_model_name}")
print(f"Accuracy: {best_accuracy:.4f} ({best_accuracy*100:.2f}%)")
print(f"Precision: {comparison_df.loc[best_model_name, 'precision']:.4f}")
print(f"Recall: {comparison_df.loc[best_model_name, 'recall']:.4f}")
print(f"F1-Score: {comparison_df.loc[best_model_name, 'f1_score']:.4f}")

# ============================================================================
# 10. SIMPAN MODEL
# ============================================================================

print("\n" + "="*70)
print("10. SIMPAN MODEL")
print("="*70)

best_models = {
    'Logistic Regression': lr_model,
    'Decision Tree': dt_model,
    'K-Nearest Neighbors': knn_model,
    'Support Vector Machine': svm_model
}

# Simpan model terbaik
model_filename = f'best_model_{best_model_name.replace(" ", "_").lower()}.pkl'
with open(model_filename, 'wb') as f:
    pickle.dump(best_models[best_model_name], f)
print(f"✓ Model terbaik disimpan: {model_filename}")

# Simpan scaler
with open('scaler.pkl', 'wb') as f:
    pickle.dump(scaler, f)
print("✓ Scaler disimpan: scaler.pkl")

print("\n" + "="*70)
print("ANALISIS SELESAI!")
print("="*70)
print("\nFile yang dihasilkan:")
print("1. distribusi_kelas.png")
print("2. correlation_matrix.png")
print("3. pairplot.png")
print("4. confusion_matrices.png")
print("5. model_comparison_results.csv")
print(f"6. {model_filename}")
print("7. scaler.pkl")
print("\n" + "="*70)

