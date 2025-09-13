<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - E-commerce</title>
    <link rel="stylesheet" href="products-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-store"></i> Manajemen Produk</h1>
            <p>Kelola produk e-commerce Anda dengan efisien</p>
        </header>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> Produk "<?php echo htmlspecialchars($_GET['product']); ?>" berhasil dihapus!
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> Gagal menghapus produk. Silakan coba lagi.
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="add-product.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>

        <div class="products-grid">
            <?php
            // Create products table if not exists
            $createTable = "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                sku VARCHAR(100) UNIQUE NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                description TEXT,
                category VARCHAR(100) NOT NULL,
                stock INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $conn->query($createTable);

            $sql = "SELECT * FROM products ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stockStatus = $row["stock"] > 0 ? 'in-stock' : 'out-of-stock';
                    $stockText = $row["stock"] > 0 ? 'Tersedia' : 'Habis';
                    echo "<div class='product-card'>";
                    echo "<div class='product-header'>";
                    echo "<h3 class='product-name'>" . htmlspecialchars($row["name"]) . "</h3>";
                    echo "<span class='product-sku'>SKU: " . htmlspecialchars($row["sku"]) . "</span>";
                    echo "</div>";
                    echo "<div class='product-details'>";
                    echo "<p class='product-price'>Rp " . number_format($row["price"], 2, ',', '.') . "</p>";
                    echo "<p class='product-category'><i class='fas fa-tag'></i> " . htmlspecialchars($row["category"]) . "</p>";
                    echo "<p class='product-stock $stockStatus'><i class='fas fa-box'></i> " . $row["stock"] . " unit - $stockText</p>";
                    echo "<p class='product-description'>" . htmlspecialchars(substr($row["description"], 0, 100)) . "...</p>";
                    echo "</div>";
                    echo "<div class='product-actions'>";
                    echo "<a href='edit-product.php?id=" . $row["id"] . "' class='btn btn-secondary'><i class='fas fa-edit'></i> Edit</a>";
                    echo "<a href='delete-product.php?id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>
                            <i class='fas fa-trash'></i> Hapus
                          </a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-products'>";
                echo "<i class='fas fa-shopping-bag'></i>";
                echo "<h3>Tidak ada produk ditemukan</h3>";
                echo "<p>Mulai dengan menambahkan produk pertama Anda!</p>";
                echo "<a href='add-product.php' class='btn btn-primary'>Tambah Produk</a>";
                echo "</div>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        // Add smooth animations
        const cards = document.querySelectorAll('.product-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    </script>
</body>
</html> 