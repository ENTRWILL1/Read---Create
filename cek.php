<?php
require_once __DIR__ . '/config/database.php';

try {
    $stmt = $pdo->query('SELECT COUNT(*) FROM projects');
    $jumlah = (int) $stmt->fetchColumn();
    echo 'Koneksi database berhasil! Jumlah project: ' . $jumlah;
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'Koneksi berhasil, tetapi tabel projects belum tersedia. Import database.sql terlebih dahulu.';
}
