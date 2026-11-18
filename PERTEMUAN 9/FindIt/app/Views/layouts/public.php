<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Find It') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">Find It</a>

        <div class="ms-auto">
            <?php if (session()->get('logged_in')): ?>
                <a href="<?= site_url('/items') ?>" class="btn btn-light btn-sm">Dashboard</a>
                <a href="<?= site_url('/logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
            <?php else: ?>
                <a href="<?= site_url('/login') ?>" class="btn btn-light btn-sm">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main>
    <?= $this->renderSection('content') ?>
</main>

<footer class="text-center text-muted py-4 mt-5">
    <small>© <?= date('Y') ?> Find It — Campus Lost & Found</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>