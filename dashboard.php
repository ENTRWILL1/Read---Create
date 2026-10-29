<?php
require_once __DIR__ . '/config/database.php';

// READ: mengambil dan menampilkan data project dari database.
$stmt = $pdo->query(
    'SELECT id, judul, deskripsi, teknologi, tautan, dibuat_pada, diedit_pada
     FROM projects
     ORDER BY id ASC'
);
$projects = $stmt->fetchAll();

$daftarPesan = [
    'tambah' => ['success', 'Project berhasil ditambahkan.'],
    'hapus'  => ['success', 'Project berhasil dihapus.'],
    'ubah'   => ['success', 'Project berhasil diperbarui.'],
    'gagal'  => ['error', 'Project tidak ditemukan atau id tidak valid.'],
];
$kodePesan = $_GET['pesan'] ?? '';
$pesan = $daftarPesan[$kodePesan] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Project</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <h1>Daftar Project</h1>
            <a href="create.php" class="btn btn-primary">+ Tambah Project</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= htmlspecialchars($pesan[0]) ?>">
                <?= htmlspecialchars($pesan[1]) ?>
            </div>
        <?php endif; ?>

        <?php if (!$projects): ?>
            <p class="empty">
                Belum ada project. <a href="create.php">Tambah project pertama</a>.
            </p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Teknologi</th>
                            <th>Tautan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($projects as $i => $project): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($project['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= nl2br(htmlspecialchars($project['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')) ?></td>
                            <td><?= htmlspecialchars($project['teknologi'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php
                                $tautan = (string) ($project['tautan'] ?? '');
                                if ($tautan !== '' && filter_var($tautan, FILTER_VALIDATE_URL)
                                    && preg_match('~^https?://~i', $tautan)):
                                ?>
                                    <a href="<?= htmlspecialchars($tautan, ENT_QUOTES, 'UTF-8') ?>"
                                       target="_blank" rel="noopener noreferrer">Buka tautan</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($project['dibuat_pada'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= !empty($project['diedit_pada']) ? htmlspecialchars(date('Y-m-d', strtotime($project['diedit_pada'])), ENT_QUOTES, 'UTF-8') : '-' ?></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?= (int) $project['id'] ?>"
                                       class="btn btn-secondary btn-small">Edit</a>
                                    <form method="post" action="delete.php"
                                          onsubmit="return confirm('Hapus project ini?');">
                                        <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
