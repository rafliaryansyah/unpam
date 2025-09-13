<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Daftar Mahasiswa</h1>
    <a href="tambah.php">+ Tambah Data Mahasiswa</a>
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM mahasiswa";
        $result = $conn->query($sql);
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row["nama"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["no_hp"] . "</td>";
            echo "<td><a href='edit.php?id=" . $row["id"] . "'>Edit</a> | <a href='hapus.php?id=" . $row["id"] . "'>Hapus</a></td>";
            echo "</tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>