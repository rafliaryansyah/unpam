<?php 
include 'db.php';

$message = '';
$messageType = '';
$product = null;

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$productId = intval($_GET['id']);

// Fetch product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();

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
        // Check if SKU already exists (excluding current product)
        $checkSku = "SELECT id FROM products WHERE sku = ? AND id != ?";
        $stmt = $conn->prepare($checkSku);
        $stmt->bind_param("si", $sku, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = "SKU sudah ada. Silakan gunakan SKU yang berbeda.";
            $messageType = "error";
        } else {
            // Update product
            $sql = "UPDATE products SET name = ?, sku = ?, price = ?, description = ?, category = ?, stock = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssii", $name, $sku, $price, $description, $category, $stock, $productId);
            
            if ($stmt->execute()) {
                $message = "Produk berhasil diperbarui!";
                $messageType = "success";
                // Refresh product data
                $product['name'] = $name;
                $product['sku'] = $sku;
                $product['price'] = $price;
                $product['description'] = $description;
                $product['category'] = $category;
                $product['stock'] = $stock;
            } else {
                $message = "Gagal memperbarui produk: " . $conn->error;
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
    <title>Edit Produk - E-commerce</title>
    <link rel="stylesheet" href="products-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="products.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Produk
        </a>
        
        <header class="header">
            <h1><i class="fas fa-edit"></i> Edit Produk</h1>
            <p>Perbarui informasi produk</p>
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
                           value="<?php echo htmlspecialchars($product['name']); ?>"
                           placeholder="Masukkan nama produk">
                </div>

                <div class="form-group">
                    <label for="sku">SKU (Kode Produk) *</label>
                    <input type="text" id="sku" name="sku" required 
                           value="<?php echo htmlspecialchars($product['sku']); ?>"
                           placeholder="contoh: PRD-001, SKU-12345">
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required 
                           value="<?php echo $product['price']; ?>"
                           placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="category">Kategori *</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Elektronik" <?php echo ($product['category'] == 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
                        <option value="Pakaian" <?php echo ($product['category'] == 'Pakaian') ? 'selected' : ''; ?>>Pakaian</option>
                        <option value="Buku" <?php echo ($product['category'] == 'Buku') ? 'selected' : ''; ?>>Buku</option>
                        <option value="Rumah & Taman" <?php echo ($product['category'] == 'Rumah & Taman') ? 'selected' : ''; ?>>Rumah & Taman</option>
                        <option value="Olahraga" <?php echo ($product['category'] == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                        <option value="Mainan" <?php echo ($product['category'] == 'Mainan') ? 'selected' : ''; ?>>Mainan</option>
                        <option value="Kecantikan" <?php echo ($product['category'] == 'Kecantikan') ? 'selected' : ''; ?>>Kecantikan</option>
                        <option value="Otomotif" <?php echo ($product['category'] == 'Otomotif') ? 'selected' : ''; ?>>Otomotif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stock">Jumlah Stok</label>
                    <input type="number" id="stock" name="stock" min="0" 
                           value="<?php echo $product['stock']; ?>"
                           placeholder="0">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" 
                              placeholder="Masukkan deskripsi produk..."><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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