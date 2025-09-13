<?php
// Konfigurasi database untuk Compute Engine
// File ini akan di-update otomatis oleh setup-db.sh script

// Konfigurasi default untuk development lokal
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cloud_db';

// Untuk production (Compute Engine), values akan di-update oleh setup-db.sh
// $host = 'YOUR_CLOUD_SQL_PUBLIC_IP';
// $username = 'root';
// $password = 'YOUR_STRONG_PASSWORD';
// $dbname = 'cloud_db';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke utf8
$conn->set_charset("utf8");

// Opsional: Tampilkan status koneksi untuk debugging (hapus di production)
// echo "✅ Database berhasil terhubung ke $dbname di $host<br>";
?>