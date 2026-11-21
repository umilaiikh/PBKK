<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Dosen - SIAKAD</title>
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
    <small class="text-white-50 px-3">PORTAL DOSEN</small>
    <a class="active" href="<?= base_url('dosen') ?>">Dashboard</a>
    <a href="<?= base_url('dosen/kelas') ?>">Mata Kuliah & Kelas</a>
    <a href="<?= base_url('dosen/bimbingan') ?>">Mahasiswa Bimbingan</a>
    <hr>
    <a href="<?= base_url('logout') ?>">Logout</a>
</aside>

<main class="main">
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Dosen</h3>
        <p class="text-secondary">Selamat datang, <?= esc(session()->get('username')) ?>.</p>
    </div>

    <?php if (!$dosen): ?>
        <div class="alert alert-warning">
            Profil dosen belum terhubung dengan akun ini. Silakan hubungi Admin untuk melengkapi data dosen.
        </div>
    <?php else: ?>
        <div class="alert alert-primary border-0">
            Selamat datang di portal dosen. Pantau mata kuliah, peserta kelas, dan mahasiswa bimbingan Anda.
        </div>

        <div class="row g-3 mb-4">
            <?php foreach ([
                ['Mata Kuliah Diampu', $jumlahMatakuliah],
                ['Jumlah Peserta Kelas', $jumlahPeserta],
                ['Mahasiswa Bimbingan', $jumlahBimbingan]
            ] as $item): ?>
            <div class="col-md-4">
                <div class="card stat-card">
                    <p class="text-secondary mb-2"><?= esc($item[0]) ?></p>
                    <div class="stat-number"><?= esc($item[1]) ?></div>
                    <small class="text-secondary">Data akademik Anda</small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card content-card">
            <h5 class="fw-bold mb-3">Mata Kuliah yang Diampu</h5>
            <?php if (empty($matakuliah)): ?>
                <p class="text-secondary mb-0">Belum ada mata kuliah yang ditugaskan kepada Anda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Semester</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($matakuliah as $mk): ?>
                            <tr>
                                <td><?= esc($mk['kode']) ?></td>
                                <td><?= esc($mk['nama']) ?></td>
                                <td><?= esc($mk['sks']) ?></td>
                                <td><?= esc($mk['semester']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary"
                                       href="<?= base_url('dosen/kelas/peserta/'.$mk['id']) ?>">
                                        Lihat Peserta
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <footer class="text-center text-secondary mt-5">
        <small>SIAKAD &copy; 2026 - Sistem Informasi Akademik</small>
    </footer>
</main>
</body>
</html>