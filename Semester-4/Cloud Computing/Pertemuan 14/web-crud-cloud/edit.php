<?php include 'db.php'; 

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

// Ambil data mahasiswa berdasarkan ID
$sql = "SELECT * FROM mahasiswa WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='index.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Data Mahasiswa</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label>NIM:</label>
            <input type="number" name="nim" value="<?php echo htmlspecialchars($row['nim']); ?>" required>
        </div>
        <button type="submit">Update</button>
        <a href="index.php">Kembali</a>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = $conn->real_escape_string($_POST['nama']);
        $nim = $conn->real_escape_string($_POST['nim']);
        
        $sql = "UPDATE mahasiswa SET nama='$nama', nim='$nim' WHERE id=$id";
        
        $result = $conn->query($sql);
        
        if ($result) {
            echo "<script>alert('Data berhasil diupdate'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Data gagal diupdate');</script>";
        }
    }
    
    $conn->close();
    ?>
</body>
</html>
