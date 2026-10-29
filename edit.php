<?php
require_once __DIR__ . '/config/database.php';

// 1. Pastikan id ada dan hanya berisi angka.
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

// 2. Ambil data lama dengan prepared statement.
$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
$stmt->execute(['id' => $_GET['id']]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Jika id tidak ditemukan, kembali ke dashboard.
if (!$project) {
    header('Location: dashboard.php?pesan=gagal');
    exit;
}

// Pesan kesalahan dari update.php (jika validasi gagal).
$daftarError = [
    'judul'     => 'Judul wajib diisi dan maksimal 100 karakter.',
    'teknologi' => 'Teknologi wajib diisi dan maksimal 100 karakter.',
    'tautan'    => 'Tautan wajib berupa URL http:// atau https:// yang valid (maks. 255 karakter).',
];
$kodeError = $_GET['error'] ?? '';
$error = $daftarError[$kodeError] ?? null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <div class="container narrow">

        <h1>Edit Project</h1>

        <div class="card">

            <p class="empty">
                Terakhir diedit:
                <?= !empty($project['diedit_pada']) ? htmlspecialchars(date('Y-m-d', strtotime($project['diedit_pada'])), ENT_QUOTES, 'UTF-8') : 'belum pernah diedit' ?>
            </p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="update.php" class="form">

                <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">

                <div class="field">
                    <label for="judul">Judul <span class="req">*</span></label>
                    <input type="text" id="judul" name="judul" maxlength="100"
                           value="<?= htmlspecialchars($project['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="field">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($project['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="field">
                    <label for="teknologi">Teknologi <span class="req">*</span></label>
                    <input type="text" id="teknologi" name="teknologi" maxlength="100"
                           value="<?= htmlspecialchars($project['teknologi'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="field">
                    <label for="tautan">Tautan <span class="req">*</span></label>
                    <input type="text" id="tautan" name="tautan" maxlength="255"
                           value="<?= htmlspecialchars($project['tautan'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
