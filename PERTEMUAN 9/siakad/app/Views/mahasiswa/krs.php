<?= $this->extend('layouts/mahasiswa') ?>
<?= $this->section('content') ?>

<h3>Pengisian Kartu Rencana Studi (KRS)</h3>

<?php if ($periode): ?>
    <div class="alert alert-info">
        Periode: <?= esc($periode['tahun_ajaran']) ?> -
        Semester <?= esc($periode['semester']) ?>.
        Batas pengisian <?= esc($periode['tanggal_mulai']) ?>
        sampai <?= esc($periode['tanggal_selesai']) ?>.
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah Mata Kuliah</h5>

            <form action="/mahasiswa/krs/tambah" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-9">
                        <select name="matakuliah_id" class="form-select" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($matakuliah as $mk): ?>
                                <option value="<?= $mk['id'] ?>">
                                    <?= esc($mk['kode']) ?> -
                                    <?= esc($mk['nama']) ?> (<?= $mk['sks'] ?> SKS)
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100">
                            Tambahkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        Periode KRS sedang ditutup.
    </div>
<?php endif ?>

<div class="card">
    <div class="card-body">
        <h5>Daftar KRS Saya</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $totalSks = 0;
                    foreach ($dataKrs as $item):
                        $totalSks += (int) $item['sks'];
                ?>
                    <tr>
                        <td><?= esc($item['kode']) ?></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['sks']) ?></td>
                        <td><?= esc($item['jadwal'] ?? '-') ?></td>
                        <td>
                            <?php if ($periode && $item['nilai_angka'] === null): ?>
                                <form action="/mahasiswa/krs/hapus/<?= $item['id'] ?>"
                                      method="post"
                                      onsubmit="return confirm('Hapus mata kuliah ini dari KRS?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Tidak dapat dihapus</span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                <?php if (empty($dataKrs)): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada mata kuliah yang diambil.
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total SKS</th>
                        <th colspan="3"><?= $totalSks ?> SKS</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <button onclick="window.print()" class="btn btn-primary">
            Cetak KRS
        </button>
    </div>
</div>

<?= $this->endSection() ?>