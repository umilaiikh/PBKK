<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SIAKAD Admin') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('admin') ?>">SIAKAD Admin</a>
        <div class="d-flex gap-3">
            <a class="text-white text-decoration-none" href="<?= base_url('admin/mahasiswa') ?>">Mahasiswa</a>
            <a class="text-white text-decoration-none" href="<?= base_url('admin/dosen') ?>">Dosen</a>
            <a class="text-white text-decoration-none" href="<?= base_url('admin/matakuliah') ?>">Mata Kuliah</a>
            <a class="text-white text-decoration-none" href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>