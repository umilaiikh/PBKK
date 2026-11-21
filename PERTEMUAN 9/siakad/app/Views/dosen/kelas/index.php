<?= $this->extend('layouts/dosen') ?>
<?= $this->section('content') ?>

<h3>Mata Kuliah yang Diajar</h3>
<p class="text-muted">Daftar mata kuliah yang menjadi tanggung jawab Anda.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Kuota</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($kelas)): ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada mata kuliah yang ditugaskan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kelas as $mk): ?>
                    <tr>
                        <td><?= esc($mk['kode']) ?></td>
                        <td><?= esc($mk['nama']) ?></td>
                        <td><?= esc($mk['sks']) ?></td>
                        <td><?= esc($mk['semester']) ?></td>
                        <td><?= esc($mk['kuota']) ?></td>
                        <td><?= esc($mk['jadwal'] ?? '-') ?></td>
                        <td>
                            <a href="/dosen/kelas/peserta/<?= $mk['id'] ?>"
                               class="btn btn-sm btn-primary">
                                Lihat Peserta
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

<?= $this->endSection() ?>