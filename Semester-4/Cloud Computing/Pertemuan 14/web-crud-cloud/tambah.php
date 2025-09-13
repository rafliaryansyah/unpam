<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Tambah Mahasiswa</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
        </div>
        <div class="form-group">
            <label>NIM:</label>
            <input type="number" name="nim" placeholder="NIM" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="index.php">Kembali</a>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = $conn->real_escape_string($_POST['nama']);
        $nim = $conn->real_escape_string($_POST['nim']);
        
        $sql = "INSERT INTO mahasiswa (nama, nim) VALUES ('$nama', '$nim')";
        $result = $conn->query($sql);
        
        if ($result) {
            echo "<script>alert('Data berhasil ditambahkan'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan');</script>";
        }
    }
    ?>
</body>
</html>