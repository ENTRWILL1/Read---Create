<?php

require_once __DIR__ . '/config/database.php';

$judul = '';
$deskripsi = '';
$teknologi = '';
$tautan = '';
$dibuat_pada = date('Y-m-d');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $teknologi = trim($_POST['teknologi'] ?? '');
    $tautan = trim($_POST['tautan'] ?? '');
    $dibuat_pada = trim($_POST['dibuat_pada'] ?? '');

    // ----- Validasi -----
    if ($judul === '') {
        $errors['judul'] = 'Judul wajib diisi.';
    } elseif ($teknologi === '') {
        $errors['teknologi'] = 'Teknologi wajib diisi.';
    } elseif ($tautan === '') {
        $errors['tautan'] = 'Tautan wajib diisi.';
    } elseif (mb_strlen($judul) > 100) {
        $errors['judul'] = 'Judul maksimal 100 karakter.';
    }

    if (mb_strlen($teknologi) > 100) {
        $errors['teknologi'] = 'Teknologi maksimal 100 karakter.';
    }

    if ($tautan !== '') {
        if (mb_strlen($tautan) > 255) {
            $errors['tautan'] = 'Tautan maksimal 255 karakter.';
        } elseif (!filter_var($tautan, FILTER_VALIDATE_URL)
                  || !preg_match('~^https?://~i', $tautan)) {
            $errors['tautan'] = 'Tautan harus berupa URL http:// atau https:// yang valid.';
        }
    }

    $tanggal = DateTime::createFromFormat('Y-m-d', $dibuat_pada);
    if ($dibuat_pada === '') {
        $errors['dibuat_pada'] = 'Tanggal wajib diisi.';
    } elseif (!$tanggal || $tanggal->format('Y-m-d') !== $dibuat_pada) {
        $errors['dibuat_pada'] = 'Format tanggal tidak valid.';
    }

    // ----- Simpan jika tidak ada error -----
    if (empty($errors)) {

        $stmt = $pdo->prepare(
            'INSERT INTO projects (judul, deskripsi, teknologi, tautan, dibuat_pada)
             VALUES (:judul, :deskripsi, :teknologi, :tautan, :dibuat_pada)'
        );

        $stmt->execute([
            'judul'       => $judul,
            'deskripsi'   => $deskripsi,
            'teknologi'   => $teknologi,
            'tautan'      => $tautan,
            'dibuat_pada' => $dibuat_pada,
        ]);

        header('Location: dashboard.php?pesan=tambah');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Project</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <div class="container narrow">

        <h1>Tambah Project</h1>

        <div class="card">

            <form method="post" action="create.php" class="form">

                <div class="field">
                    <label for="judul">Judul <span class="req">*</span></label>
                    <input type="text" id="judul" name="judul" maxlength="100"
                           value="<?= htmlspecialchars($judul) ?>">
                    <?php if (isset($errors['judul'])): ?>
                        <small class="error"><?= htmlspecialchars($errors['judul']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($deskripsi) ?></textarea>
                </div>

                <div class="field">
                    <label for="teknologi">Teknologi</label>
                    <input type="text" id="teknologi" name="teknologi" maxlength="100"
                           placeholder="contoh: PHP, MySQL, Bootstrap"
                           value="<?= htmlspecialchars($teknologi) ?>">
                    <?php if (isset($errors['teknologi'])): ?>
                        <small class="error"><?= htmlspecialchars($errors['teknologi']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="tautan">Tautan</label>
                    <input type="text" id="tautan" name="tautan" maxlength="255"
                           placeholder="https://github.com/username/project"
                           value="<?= htmlspecialchars($tautan) ?>">
                    <?php if (isset($errors['tautan'])): ?>
                        <small class="error"><?= htmlspecialchars($errors['tautan']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="dibuat_pada">Tanggal Dibuat <span class="req">*</span></label>
                    <input type="date" id="dibuat_pada" name="dibuat_pada"
                           value="<?= htmlspecialchars($dibuat_pada) ?>">
                    <?php if (isset($errors['dibuat_pada'])): ?>
                        <small class="error"><?= htmlspecialchars($errors['dibuat_pada']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
