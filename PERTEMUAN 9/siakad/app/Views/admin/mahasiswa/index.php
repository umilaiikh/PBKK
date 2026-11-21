<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Data Mahasiswa</h3>
    <a href="<?= base_url('admin/mahasiswa/create') ?>" class="btn btn-primary">Tambah Mahasiswa</a>
</div>

<div class="card shadow-sm">
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead><tr>
    <th>NIM</th><th>Nama</th><th>Program Studi</th>
    <th>Angkatan</th><th>Dosen Wali</th><th>Aksi</th>
</tr></thead>
<tbody>
<?php foreach ($mahasiswa as $m): ?>
<tr>
    <td><?= esc($m['nim']) ?></td>
    <td><?= esc($m['nama']) ?></td>
    <td><?= esc($m['program_studi']) ?></td>
    <td><?= esc($m['angkatan']) ?></td>
    <td><?= esc($m['nama_dosen'] ?? '-') ?></td>
    <td class="d-flex gap-1">
        <a class="btn btn-sm btn-warning" href="<?= base_url('admin/mahasiswa/edit/'.$m['id']) ?>">Edit</a>
        <form method="post" action="<?= base_url('admin/mahasiswa/delete/'.$m['id']) ?>" onsubmit="return confirm('Hapus mahasiswa ini?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($mahasiswa)): ?>
<tr><td colspan="6" class="text-center">Belum ada data mahasiswa.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>

<?= $this->endSection() ?>