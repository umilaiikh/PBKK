<?= $this->extend('layouts/dosen') ?>
<?= $this->section('content') ?>

<h3>Peserta Mata Kuliah</h3>

<div class="card mb-3">
    <div class="card-body">
        <h5><?= esc($kelas['nama']) ?></h5>
        <p class="mb-0">
            Kode: <?= esc($kelas['kode']) ?> |
            SKS: <?= esc($kelas['sks']) ?>
        </p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($peserta)): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada mahasiswa yang mengambil mata kuliah ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($peserta as $p): ?>
                    <tr>
                        <td><?= esc($p['nim']) ?></td>
                        <td><?= esc($p['nama']) ?></td>
                        <td><?= esc($p['nilai_angka'] ?? '-') ?></td>
                        <td><?= esc($p['nilai_huruf'] ?? '-') ?></td>
                        <td>
                            <a href="/dosen/kelas/nilai/<?= $p['id'] ?>"
                               class="btn btn-sm btn-success">
                                Input / Ubah Nilai
                            </a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="/dosen/kelas" class="btn btn-secondary mt-3">Kembali</a>

<?= $this->endSection() ?>