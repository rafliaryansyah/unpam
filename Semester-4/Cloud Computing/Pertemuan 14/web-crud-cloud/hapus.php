<?php 
include 'db.php';
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='index.php';</script>";
    exit();
}

$id = (int)$_GET['id'];

$check_sql = "SELECT * FROM mahasiswa WHERE id = $id";
$check_result = $conn->query($check_sql);

if (!$check_result || $check_result->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='index.php';</script>";
    exit();
}

$sql = "DELETE FROM mahasiswa WHERE id = $id";

$result = $conn->query($sql);

if ($result) {
    echo "<script>alert('Data berhasil dihapus'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Data gagal dihapus'); window.location.href='index.php';</script>";
}

$conn->close();
?> 