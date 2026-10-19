<?php
require_once __DIR__ . '/config/database.php';

// READ: mengambil dan menampilkan data project dari database.
$stmt = $pdo->query(
    'SELECT id, judul, deskripsi, teknologi, tautan, dibuat_pada
     FROM projects
     ORDER BY dibuat_pada DESC, id DESC'
);
$projects = $stmt->fetchAll();

$daftarPesan = [
    'tambah' => ['success', 'Project berhasil ditambahkan.'],
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
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Teknologi</th>
                            <th>Tautan</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= (int) $project['id'] ?></td>
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
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
