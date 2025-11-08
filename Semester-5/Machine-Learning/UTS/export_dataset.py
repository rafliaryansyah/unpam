"""
Script untuk export dataset Iris ke file CSV
UTS Machine Learning - Universitas Pamulang
"""

import pandas as pd
from sklearn.datasets import load_iris

# Load Iris dataset
print("Loading Iris dataset...")
iris = load_iris()

# Create DataFrame
df = pd.DataFrame(data=iris.data, columns=iris.feature_names)
df['target'] = iris.target
df['species'] = df['target'].map({0: 'setosa', 1: 'versicolor', 2: 'virginica'})

# Save to CSV
output_file = 'dataset_iris.csv'
df.to_csv(output_file, index=False)

print(f"✓ Dataset berhasil disimpan ke: {output_file}")
print(f"  Total rows: {len(df)}")
print(f"  Columns: {', '.join(df.columns)}")
print(f"\nPreview data:")
print(df.head())

