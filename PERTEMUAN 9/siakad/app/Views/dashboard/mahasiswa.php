<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Mahasiswa - SIAKAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f4f6fa;font-family:Arial,sans-serif}
        .sidebar{width:250px;min-height:100vh;background:#172554;color:white;position:fixed;padding:25px 18px}
        .brand{font-size:24px;font-weight:bold;margin-bottom:35px;padding:8px}
        .sidebar a{display:block;color:#dbeafe;text-decoration:none;padding:13px 15px;border-radius:8px;margin-bottom:7px}
        .sidebar a:hover,.sidebar a.active{background:#2563eb;color:white}
        .main{margin-left:250px;padding:35px}
        .stat-card,.content-card{border:0;border-radius:14px;padding:22px;box-shadow:0 3px 12px #00000008;height:100%}
        .stat-number{font-size:32px;font-weight:bold;color:#172554}
        @media(max-width:768px){.sidebar{position:relative;width:100%;min-height:auto}.main{margin-left:0;padding:20px}}
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand">SIAKAD</div>
    <small class="text-white-50 px-3">PORTAL MAHASISWA</small>
    <a class="active" href="<?= base_url('mahasiswa') ?>">Dashboard</a>
    <a href="<?= base_url('mahasiswa/krs') ?>">Kartu Rencana Studi (KRS)</a>
    <a href="<?= base_url('mahasiswa/khs') ?>">Kartu Hasil Studi (KHS)</a>
    <a href="<?= base_url('mahasiswa/transkrip') ?>">Transkrip Nilai</a>
    <hr>
    <a href="<?= base_url('logout') ?>">Logout</a>
</aside>

<main class="main">
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Mahasiswa</h3>
        <p class="text-secondary">Selamat datang, <?= esc(session()->get('username')) ?>.</p>
    </div>

    <?php if (!$mahasiswa): ?>
        <div class="alert alert-warning">
            Profil mahasiswa belum terhubung dengan akun ini. Silakan hubungi Admin untuk melengkapi data mahasiswa.
        </div>
    <?php else: ?>
        <div class="alert alert-primary border-0">
            Selamat datang di portal akademik. Pantau rencana studi dan perkembangan akademik Anda.
        </div>

        <div class="card content-card mb-4">
            <h5 class="fw-bold mb-3">Informasi Mahasiswa</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <span class="text-secondary">Nama</span>
                    <div class="fw-semibold"><?= esc($mahasiswa['nama']) ?></div>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="text-secondary">NIM</span>
                    <div class="fw-semibold"><?= esc($mahasiswa['nim']) ?></div>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="text-secondary">Program Studi</span>
                    <div class="fw-semibold"><?= esc($mahasiswa['program_studi']) ?></div>
                </div>
                <div class="col-md-6 mb-2">
                    <span class="text-secondary">Angkatan</span>
                    <div class="fw-semibold"><?= esc($mahasiswa['angkatan']) ?></div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Ringkasan Akademik</h5>
        <div class="row g-3 mb-4">
            <?php foreach ([
                ['Mata Kuliah Diambil', $jumlahKrs],
                ['Total SKS', $totalSks],
                ['Nilai Tersedia', $jumlahNilai],
                ['IPK Sementara', number_format($ipk, 2)]
            ] as $item): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card">
                    <p class="text-secondary mb-2"><?= esc($item[0]) ?></p>
                    <div class="stat-number"><?= esc($item[1]) ?></div>
                    <small class="text-secondary">Ringkasan akademik</small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card content-card">
            <h5 class="fw-bold mb-3">Layanan Akademik</h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('mahasiswa/krs') ?>" class="btn btn-primary">Isi KRS</a>
                <a href="<?= base_url('mahasiswa/khs') ?>" class="btn btn-outline-primary">Lihat KHS</a>
                <a href="<?= base_url('mahasiswa/transkrip') ?>" class="btn btn-outline-primary">Lihat Transkrip</a>
            </div>
        </div>
    <?php endif; ?>

    <footer class="text-center text-secondary mt-5">
        <small>SIAKAD &copy; 2026 - Sistem Informasi Akademik</small>
    </footer>
</main>
</body>
</html>