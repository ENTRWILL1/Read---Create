<?php
// Sesuaikan nama database dengan database yang dibuat dari database.sql.
$host = '127.0.0.1';
$db   = 'read - create';
$user = 'root';
$pass = ''; // Default Laragon biasanya tanpa password.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Koneksi database gagal. Periksa database dan pengaturan di config/database.php.');
}
