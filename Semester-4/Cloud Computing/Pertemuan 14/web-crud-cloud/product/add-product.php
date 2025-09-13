<?php 
include 'db.php';

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $sku = trim($_POST['sku']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $stock = intval($_POST['stock']);
    
    // Validation
    if (empty($name) || empty($sku) || $price <= 0 || empty($category)) {
        $message = "Mohon isi semua kolom wajib dengan nilai yang valid.";
        $messageType = "error";
    } else {
        // Check if SKU already exists
        $checkSku = "SELECT id FROM products WHERE sku = ?";
        $stmt = $conn->prepare($checkSku);
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = "SKU sudah ada. Silakan gunakan SKU yang berbeda.";
            $messageType = "error";
        } else {
            // Insert new product
            $sql = "INSERT INTO products (name, sku, price, description, category, stock) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $name, $sku, $price, $description, $category, $stock);
            
            if ($stmt->execute()) {
                $message = "Produk berhasil ditambahkan!";
                $messageType = "success";
                // Clear form data
                $name = $sku = $description = $category = '';
                $price = $stock = 0;
            } else {
                $message = "Gagal menambahkan produk: " . $conn->error;
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - E-commerce</title>
    <link rel="stylesheet" href="products-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="products.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Produk
        </a>
        
        <header class="header">
            <h1><i class="fas fa-plus-circle"></i> Tambah Produk Baru</h1>
            <p>Buat produk baru untuk toko e-commerce Anda</p>
        </header>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Produk *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                           placeholder="Masukkan nama produk">
                </div>

                <div class="form-group">
                    <label for="sku">SKU (Kode Produk) *</label>
                    <input type="text" id="sku" name="sku" required 
                           value="<?php echo isset($sku) ? htmlspecialchars($sku) : ''; ?>"
                           placeholder="contoh: PRD-001, SKU-12345">
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required 
                           value="<?php echo isset($price) ? $price : ''; ?>"
                           placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="category">Kategori *</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Elektronik" <?php echo (isset($category) && $category == 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
                        <option value="Pakaian" <?php echo (isset($category) && $category == 'Pakaian') ? 'selected' : ''; ?>>Pakaian</option>
                        <option value="Buku" <?php echo (isset($category) && $category == 'Buku') ? 'selected' : ''; ?>>Buku</option>
                        <option value="Rumah & Taman" <?php echo (isset($category) && $category == 'Rumah & Taman') ? 'selected' : ''; ?>>Rumah & Taman</option>
                        <option value="Olahraga" <?php echo (isset($category) && $category == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                        <option value="Mainan" <?php echo (isset($category) && $category == 'Mainan') ? 'selected' : ''; ?>>Mainan</option>
                        <option value="Kecantikan" <?php echo (isset($category) && $category == 'Kecantikan') ? 'selected' : ''; ?>>Kecantikan</option>
                        <option value="Otomotif" <?php echo (isset($category) && $category == 'Otomotif') ? 'selected' : ''; ?>>Otomotif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stock">Jumlah Stok</label>
                    <input type="number" id="stock" name="stock" min="0" 
                           value="<?php echo isset($stock) ? $stock : '0'; ?>"
                           placeholder="0">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" 
                              placeholder="Masukkan deskripsi produk..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tambah Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-generate SKU based on product name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value.trim();
            if (name && !document.getElementById('sku').value) {
                const sku = 'PRD-' + name.replace(/\s+/g, '-').toUpperCase().substring(0, 8) + '-' + 
                           Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                document.getElementById('sku').value = sku;
            }
        });

        // Format price input
        document.getElementById('price').addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    </script>
</body>
</html> 