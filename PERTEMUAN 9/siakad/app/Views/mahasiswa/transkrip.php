<?= $this->extend('layouts/mahasiswa') ?>
<?= $this->section('content') ?>

<h3>Transkrip Akademik</h3>

<div class="card">
    <div class="card-body">
        <h5>Data Mahasiswa</h5>
        <p class="mb-1">NIM: <?= esc($mhs['nim']) ?></p>
        <p class="mb-1">Nama: <?= esc($mhs['nama']) ?></p>
        <p class="mb-1">Program Studi: <?= esc($mhs['program_studi']) ?></p>
        <p>Angkatan: <?= esc($mhs['angkatan']) ?></p>

        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Nilai Angka</th>
                        <th>Huruf</th>
                        <th>Bobot</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($nilai as $i => $item): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($item['kode']) ?></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['sks']) ?></td>
                        <td><?= esc($item['nilai_angka']) ?></td>
                        <td><?= esc($item['nilai_huruf']) ?></td>
                        <td><?= esc($item['bobot']) ?></td>
                    </tr>
                <?php endforeach ?>

                <?php if (empty($nilai)): ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada nilai yang tercatat.
                        </td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>

        <hr>

        <h5>Total SKS: <?= $totalSks ?></h5>
        <h5>Indeks Prestasi Kumulatif (IPK): <?= number_format($ipk, 2) ?></h5>

        <button onclick="window.print()" class="btn btn-primary mt-3">
            Cetak Transkrip
        </button>
    </div>
</div>

<?= $this->endSection() ?>