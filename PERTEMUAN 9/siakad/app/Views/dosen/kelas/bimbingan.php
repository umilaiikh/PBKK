<?= $this->extend('layouts/dosen') ?>
<?= $this->section('content') ?>

<h3>Mahasiswa Bimbingan</h3>
<p class="text-muted">Daftar mahasiswa yang menjadi tanggung jawab dosen wali.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($mahasiswa)): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada mahasiswa bimbingan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mahasiswa as $m): ?>
                    <tr>
                        <td><?= esc($m['nim']) ?></td>
                        <td><?= esc($m['nama']) ?></td>
                        <td><?= esc($m['program_studi']) ?></td>
                        <td><?= esc($m['angkatan']) ?></td>
                        <td><?= esc($m['email'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>