<?php
require_once __DIR__ . '/config/database.php';

// DELETE: menghapus satu project berdasarkan id yang dikirim lewat POST.
$pesan = 'gagal';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Hanya jalankan DELETE jika id berupa angka bulat positif.
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    if ($id !== false && $id > 0) {
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$id]);

        // rowCount() = jumlah baris yang benar-benar terhapus.
        if ($stmt->rowCount() > 0) {
            $pesan = 'hapus';
        }
    }
}

header('Location: dashboard.php?pesan=' . $pesan);
exit;
