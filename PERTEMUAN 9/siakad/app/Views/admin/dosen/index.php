<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Data Dosen</h3>

<div class="card card-body mb-4">
    <h5>Tambah Dosen</h5>
    <form method="post" action="<?= base_url('admin/dosen/store') ?>">
        <?= csrf_field() ?>
        <div class="row g-2">
            <div class="col-md-3">
                <input name="nip" class="form-control" placeholder="NIP" required>
            </div>
            <div class="col-md-3">
                <input name="nama" class="form-control" placeholder="Nama dosen" required>
            </div>
            <div class="col-md-3">
                <input name="email" type="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-md-3">
                <input name="no_telepon" class="form-control" placeholder="No. telepon">
            </div>
        </div>
        <button class="btn btn-primary mt-3">Tambah Dosen</button>
        <p class="text-muted small mt-2 mb-0">NIP digunakan sebagai username dan password awal dosen.</p>
    </form>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr>
            <th>NIP</th><th>Nama</th><th>Email</th><th>No. Telepon</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        <?php foreach ($dosen as $d): ?>
        <tr>
            <td><?= esc($d['nip']) ?></td>
            <td><?= esc($d['nama']) ?></td>
            <td><?= esc($d['email'] ?? '-') ?></td>
            <td><?= esc($d['no_telepon'] ?? '-') ?></td>
            <td>
                <form method="post" action="<?= base_url('admin/dosen/delete/'.$d['id']) ?>" onsubmit="return confirm('Hapus dosen ini?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($dosen)): ?>
        <tr><td colspan="5" class="text-center">Belum ada data dosen.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?= $this->endSection() ?>