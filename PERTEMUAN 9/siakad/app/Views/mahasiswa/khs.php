<?= $this->extend('layouts/mahasiswa') ?>
<?= $this->section('content') ?>

<h3>Kartu Hasil Studi (KHS)</h3>

<?php if (empty($semesterList)): ?>
    <div class="alert alert-info">
        Belum ada nilai yang dapat ditampilkan.
    </div>
<?php endif ?>

<?php foreach ($semesterList as $namaSemester => $semester): ?>
    <div class="card mb-4">
        <div class="card-header">
            <strong><?= esc($namaSemester) ?></strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Nilai Angka</th>
                            <th>Huruf</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($semester['items'] as $item): ?>
                        <tr>
                            <td><?= esc($item['kode']) ?></td>
                            <td><?= esc($item['nama']) ?></td>
                            <td><?= esc($item['sks']) ?></td>
                            <td><?= esc($item['nilai_angka']) ?></td>
                            <td><?= esc($item['nilai_huruf']) ?></td>
                            <td><?= esc($item['bobot']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <p class="mb-1">
                Total SKS: <strong><?= $semester['total_sks'] ?></strong>
            </p>
            <p class="mb-0">
                Indeks Prestasi Semester (IPS):
                <strong><?= number_format($semester['ip'], 2) ?></strong>
            </p>
        </div>
    </div>
<?php endforeach ?>

<button onclick="window.print()" class="btn btn-primary">
    Cetak KHS
</button>

<?= $this->endSection() ?>