<?php
require_once __DIR__ . '/config/database.php';

// Hanya terima data dari form (POST) dan id harus berupa angka.
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header('Location: dashboard.php?pesan=gagal');
    exit;
}

$id        = $_POST['id'];
$judul     = trim($_POST['judul'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$teknologi = trim($_POST['teknologi'] ?? '');
$tautan    = trim($_POST['tautan'] ?? '');

// ----- Validasi ulang di sisi server -----
$error = '';
if ($judul === '' || mb_strlen($judul) > 100) {
    $error = 'judul';
} elseif ($teknologi === '' || mb_strlen($teknologi) > 100) {
    $error = 'teknologi';
} elseif ($tautan === '' || mb_strlen($tautan) > 255
          || !filter_var($tautan, FILTER_VALIDATE_URL)
          || !preg_match('~^https?://~i', $tautan)) {
    $error = 'tautan';
}

// Jika tidak valid, kembali ke form edit dengan pesan kesalahan.
if ($error !== '') {
    header('Location: edit.php?id=' . $id . '&error=' . $error);
    exit;
}

// ----- UPDATE dengan prepared statement -----
$stmt = $pdo->prepare(
    'UPDATE projects
     SET judul = :judul, deskripsi = :deskripsi, teknologi = :teknologi,
         tautan = :tautan, diedit_pada = CURDATE()
     WHERE id = :id'
);
$stmt->execute([
    'judul'     => $judul,
    'deskripsi' => $deskripsi,
    'teknologi' => $teknologi,
    'tautan'    => $tautan,
    'id'        => $id,
]);

header('Location: dashboard.php?pesan=ubah');
exit;
