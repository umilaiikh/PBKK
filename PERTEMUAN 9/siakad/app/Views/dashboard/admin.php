<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - SIAKAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f4f6fa;font-family:Arial,sans-serif}
        .sidebar{width:250px;min-height:100vh;background:#172554;color:white;position:fixed;padding:25px 18px}
        .brand{font-size:24px;font-weight:bold;margin-bottom:35px;padding:8px}
        .sidebar a{display:block;color:#dbeafe;text-decoration:none;padding:13px 15px;border-radius:8px;margin-bottom:7px}
        .sidebar a:hover,.sidebar a.active{background:#2563eb;color:white}
        .main{margin-left:250px;padding:35px}
        .stat-card,.menu-card{border:0;border-radius:14px;padding:22px;box-shadow:0 3px 12px #00000008;height:100%}
        .stat-number{font-size:32px;font-weight:bold;color:#172554}
        .menu-card a{color:#2563eb;text-decoration:none;font-weight:bold}
        @media(max-width:768px){.sidebar{position:relative;width:100%;min-height:auto}.main{margin-left:0;padding:20px}}
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand">SIAKAD</div>
    <small class="text-white-50 px-3">ADMINISTRATOR</small>
    <a class="active" href="<?= base_url('admin') ?>">Dashboard</a>
    <a href="<?= base_url('admin/mahasiswa') ?>">Data Mahasiswa</a>
    <a href="<?= base_url('admin/dosen') ?>">Data Dosen</a>
    <a href="<?= base_url('admin/matakuliah') ?>">Data Mata Kuliah</a>
    <hr>
    <a href="<?= base_url('logout') ?>">Logout</a>
</aside>

<main class="main">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h3 class="fw-bold">Dashboard Admin</h3>
            <p class="text-secondary">Sistem Informasi Akademik</p>
        </div>
        <div class="bg-white p-3 rounded shadow-sm">
            Admin: <strong><?= esc(session()->get('username')) ?></strong>
        </div>
    </div>

    <div class="alert alert-primary border-0">
        Selamat datang di Sistem Informasi Akademik. Kelola data akademik melalui menu yang tersedia.
    </div>

    <h5 class="fw-bold mb-3">Ringkasan Data</h5>
    <div class="row g-3 mb-4">
        <?php foreach ([
            ['Mahasiswa', $jumlahMahasiswa],
            ['Dosen', $jumlahDosen],
            ['Mata Kuliah', $jumlahMatakuliah],
            ['Total Pengguna', $jumlahUser]
        ] as $item): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card">
                <p class="text-secondary mb-2"><?= esc($item[0]) ?></p>
                <div class="stat-number"><?= esc($item[1]) ?></div>
                <small class="text-secondary">Data terdaftar</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <h5 class="fw-bold mb-3">Pengelolaan Akademik</h5>
    <div class="row g-3">
        <?php foreach ([
            ['Data Mahasiswa','Mengelola informasi mahasiswa.','admin/mahasiswa','Kelola Mahasiswa'],
            ['Data Dosen','Mengelola informasi dosen.','admin/dosen','Kelola Dosen'],
            ['Data Mata Kuliah','Mengelola mata kuliah dan dosen pengampu.','admin/matakuliah','Kelola Mata Kuliah']
        ] as $item): ?>
        <div class="col-md-4">
            <div class="card menu-card">
                <h5 class="fw-bold"><?= esc($item[0]) ?></h5>
                <p class="text-secondary"><?= esc($item[1]) ?></p>
                <a href="<?= base_url($item[2]) ?>"><?= esc($item[3]) ?> →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <footer class="text-center text-secondary mt-5">
        <small>SIAKAD &copy; 2026 - Sistem Informasi Akademik</small>
    </footer>
</main>
</body>
</html>