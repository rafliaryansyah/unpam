<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah</title>
</head>
<body>
    <h2>Tambah Mahasiswa</h2>
    <form action="POST">
        Name Lengkap: <input type="text" name="fullName" placeholder="Nama Lengkap">
        NIM: <input type="text" name="nim" placeholder="NIM">
        <button type="submit">Simpan</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullName = $_POST['fullName'];
        $nim = $_POST['nim'];
        $sql = "INSERT INTO mahasiswa (full_name, nim) VALUES ('$fullName', '$nim')";
        if (mysqli_query($conn, $sql)) {
            echo "Data berhasil ditambahkan";
            header("Location: index.php");
        } else {
            echo "Data gagal ditambahkan";
            header("Location: index.php");
        }
    }
    ?>
</body>
</html>