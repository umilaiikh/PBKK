<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $editing = !empty($matakuliah); ?>

<h3 class="mb-3"><?= $editing ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' ?></h3>

<form method="post"
 action="<?= $editing ? base_url('admin/matakuliah/update/'.$matakuliah['id']) : base_url('admin/matakuliah/store') ?>"
 class="card card-body shadow-sm">
 <?= csrf_field() ?>

 <div class="mb-3">
    <label class="form-label">Kode Mata Kuliah</label>
    <input name="kode" class="form-control" value="<?= old('kode', $matakuliah['kode'] ?? '') ?>" <?= $editing ? 'readonly' : 'required' ?>>
 </div>
 <div class="mb-3">
    <label class="form-label">Nama Mata Kuliah</label>
    <input name="nama" class="form-control" value="<?= old('nama', $matakuliah['nama'] ?? '') ?>" required>
 </div>
 <div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">SKS</label>
        <input type="number" name="sks" class="form-control" min="1" max="6" value="<?= old('sks', $matakuliah['sks'] ?? 3) ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Semester</label>
        <input type="number" name="semester" class="form-control" min="1" max="8" value="<?= old('semester', $matakuliah['semester'] ?? 1) ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kuota</label>
        <input type="number" name="kuota" class="form-control" min="1" value="<?= old('kuota', $matakuliah['kuota'] ?? 40) ?>" required>
    </div>
 </div>
 <div class="mb-3">
    <label class="form-label">Semester Akademik (opsional)</label>
    <input type="number" name="semester_akademik" class="form-control" min="1" max="8" value="<?= old('semester_akademik', $matakuliah['semester_akademik'] ?? '') ?>">
 </div>
 <div class="mb-3">
    <label class="form-label">Dosen Pengampu</label>
    <select name="dosen_id" class="form-select">
        <option value="">Belum ditentukan</option>
        <?php foreach ($dosen as $d): ?>
        <option value="<?= $d['id'] ?>" <?= old('dosen_id', $matakuliah['dosen_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
            <?= esc($d['nama']) ?>
        </option>
        <?php endforeach; ?>
    </select>
 </div>
 <div class="mb-3">
    <label class="form-label">Jadwal</label>
    <input name="jadwal" class="form-control" placeholder="Contoh: Senin, 08.00–09.40" value="<?= old('jadwal', $matakuliah['jadwal'] ?? '') ?>">
 </div>
 <div class="d-flex gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/matakuliah') ?>" class="btn btn-secondary">Kembali</a>
 </div>
</form>

<?= $this->endSection() ?>