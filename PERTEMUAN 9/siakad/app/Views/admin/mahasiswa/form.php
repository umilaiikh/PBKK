<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $editing = !empty($mahasiswa); ?>

<h3 class="mb-3"><?= $editing ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h3>

<form method="post"
 action="<?= $editing ? base_url('admin/mahasiswa/update/'.$mahasiswa['id']) : base_url('admin/mahasiswa/store') ?>"
 class="card card-body shadow-sm">
 <?= csrf_field() ?>

 <?php if (!$editing): ?>
 <div class="mb-3">
    <label class="form-label">NIM</label>
    <input name="nim" class="form-control" value="<?= old('nim') ?>" required>
    <small class="text-muted">NIM akan menjadi username dan password awal.</small>
 </div>
 <?php endif; ?>

 <div class="mb-3">
    <label class="form-label">Nama</label>
    <input name="nama" class="form-control" value="<?= old('nama', $mahasiswa['nama'] ?? '') ?>" required>
 </div>
 <div class="mb-3">
    <label class="form-label">Program Studi</label>
    <input name="program_studi" class="form-control" value="<?= old('program_studi', $mahasiswa['program_studi'] ?? '') ?>" required>
 </div>
 <div class="mb-3">
    <label class="form-label">Angkatan</label>
    <input type="number" name="angkatan" class="form-control" value="<?= old('angkatan', $mahasiswa['angkatan'] ?? date('Y')) ?>" required>
 </div>
 <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= old('email', $mahasiswa['email'] ?? '') ?>">
 </div>
 <div class="mb-3">
    <label class="form-label">Dosen Wali</label>
    <select name="dosen_wali_id" class="form-select">
        <option value="">Belum ditentukan</option>
        <?php foreach ($dosen as $d): ?>
        <option value="<?= $d['id'] ?>" <?= old('dosen_wali_id', $mahasiswa['dosen_wali_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
            <?= esc($d['nama']) ?>
        </option>
        <?php endforeach; ?>
    </select>
 </div>
 <div class="d-flex gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/mahasiswa') ?>" class="btn btn-secondary">Kembali</a>
 </div>
</form>

<?= $this->endSection() ?>