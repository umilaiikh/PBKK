<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Data Mata Kuliah</h3>
    <a href="<?= base_url('admin/matakuliah/create') ?>" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>

<div class="card shadow-sm">
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead><tr>
    <th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th>
    <th>Semester</th><th>Dosen Pengampu</th><th>Kuota</th><th>Aksi</th>
</tr></thead>
<tbody>
<?php foreach ($matakuliah as $m): ?>
<tr>
    <td><?= esc($m['kode']) ?></td>
    <td><?= esc($m['nama']) ?></td>
    <td><?= esc($m['sks']) ?></td>
    <td><?= esc($m['semester']) ?></td>
    <td><?= esc($m['nama_dosen'] ?? '-') ?></td>
    <td><?= esc($m['kuota']) ?></td>
    <td class="d-flex gap-1">
        <a href="<?= base_url('admin/matakuliah/edit/'.$m['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
        <form method="post" action="<?= base_url('admin/matakuliah/delete/'.$m['id']) ?>" onsubmit="return confirm('Hapus mata kuliah ini?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($matakuliah)): ?>
<tr><td colspan="7" class="text-center">Belum ada mata kuliah.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div></div>

<?= $this->endSection() ?>