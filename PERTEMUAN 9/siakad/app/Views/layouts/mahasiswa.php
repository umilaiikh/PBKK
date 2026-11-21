<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mahasiswa - SIAKAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="/mahasiswa">SIAKAD Mahasiswa</a>

        <div class="navbar-nav">
            <a class="nav-link" href="/mahasiswa">Dashboard</a>
            <a class="nav-link" href="/mahasiswa/krs">KRS</a>
            <a class="nav-link" href="/mahasiswa/khs">KHS</a>
            <a class="nav-link" href="/mahasiswa/transkrip">Transkrip</a>
            <a class="nav-link" href="/logout">Logout</a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>

</body>
</html>