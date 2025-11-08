#!/bin/bash

# Setup and Run Script for UTS Machine Learning
# Proyek Klasifikasi Iris

echo "=================================================="
echo "   UTS Machine Learning - Klasifikasi Iris"
echo "   Universitas Pamulang"
echo "=================================================="
echo ""

# Check Python version
echo "🔍 Checking Python version..."
python3 --version

if [ $? -ne 0 ]; then
    echo "❌ Python3 not found! Please install Python 3.8 or higher."
    exit 1
fi

echo "✅ Python found!"
echo ""

# Create virtual environment (optional but recommended)
echo "📦 Creating virtual environment..."
python3 -m venv venv

if [ $? -eq 0 ]; then
    echo "✅ Virtual environment created!"
    
    # Activate virtual environment
    echo "🔄 Activating virtual environment..."
    source venv/bin/activate
    echo "✅ Virtual environment activated!"
else
    echo "⚠️  Could not create virtual environment. Continuing without it..."
fi

echo ""

# Install dependencies
echo "📥 Installing dependencies..."
pip install --upgrade pip
pip install -r requirements.txt

if [ $? -eq 0 ]; then
    echo "✅ All dependencies installed successfully!"
else
    echo "❌ Failed to install dependencies. Please check requirements.txt"
    exit 1
fi

echo ""
echo "=================================================="
echo "   Installation Complete!"
echo "=================================================="
echo ""
echo "📚 To run the Jupyter Notebook:"
echo "   jupyter notebook klasifikasi_iris.ipynb"
echo ""
echo "📊 To view the report:"
echo "   cat LAPORAN_UTS_MACHINE_LEARNING.md"
echo ""
echo "🔧 If you created a virtual environment, remember to:"
echo "   source venv/bin/activate"
echo ""
echo "Happy coding! 🚀"
echo "=================================================="

