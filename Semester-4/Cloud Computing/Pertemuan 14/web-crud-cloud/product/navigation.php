<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web CRUD Cloud - Navigasi</title>
    <link rel="stylesheet" href="products-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-cloud"></i> Web CRUD Cloud</h1>
            <p>Pilih sistem manajemen Anda</p>
        </header>

        <div class="products-grid">
            <div class="product-card">
                <div class="product-header">
                    <h3 class="product-name">
                        <i class="fas fa-graduation-cap"></i> Manajemen Mahasiswa
                    </h3>
                    <span class="product-sku">Sistem Asli</span>
                </div>
                <div class="product-details">
                    <p class="product-description">
                        Sistem manajemen mahasiswa sederhana dengan operasi CRUD dasar untuk mengelola data mahasiswa (Nama dan NIM).
                    </p>
                </div>
                <div class="product-actions">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-users"></i> Kelola Mahasiswa
                    </a>
                </div>
            </div>

            <div class="product-card">
                <div class="product-header">
                    <h3 class="product-name">
                        <i class="fas fa-store"></i> Manajemen Produk
                    </h3>
                    <span class="product-sku">Sistem E-commerce Baru</span>
                </div>
                <div class="product-details">
                    <p class="product-description">
                        Sistem manajemen produk e-commerce modern dengan UI yang indah, menampilkan katalog produk, kategori, harga, dan manajemen inventori.
                    </p>
                </div>
                <div class="product-actions">
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Kelola Produk
                    </a>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <p style="color: white; opacity: 0.8;">
                <i class="fas fa-info-circle"></i> 
                Kedua sistem menggunakan koneksi database yang sama tetapi mengelola jenis data yang berbeda.
            </p>
        </div>
    </div>

    <script>
        // Add smooth animations
        const cards = document.querySelectorAll('.product-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.2}s`;
        });
    </script>
</body>
</html> 