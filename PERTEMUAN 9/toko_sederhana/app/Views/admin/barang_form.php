<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-md-8"><div class="card p-4">
<h2><?= $barang ? 'Edit Barang' : 'Tambah Barang' ?></h2>
<form method="post" action="/admin/barang/<?= $aksi ?>"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama barang</label><input name="nama" class="form-control" value="<?= old('nama', $barang['nama'] ?? '') ?>" required></div>
<div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"><?= old('deskripsi', $barang['deskripsi'] ?? '') ?></textarea></div>
<div class="row"><div class="col-md-6 mb-3"><label class="form-label">Harga</label><input type="number" min="0" step="0.01" name="harga" class="form-control" value="<?= old('harga', $barang['harga'] ?? '') ?>" required></div>
<div class="col-md-6 mb-3"><label class="form-label">Stok</label><input type="number" min="0" name="stok" class="form-control" value="<?= old('stok', $barang['stok'] ?? '') ?>" required></div></div>
<div class="mb-3"><label class="form-label">URL gambar (opsional)</label><input name="gambar" type="url" class="form-control" value="<?= old('gambar', $barang['gambar'] ?? '') ?>" placeholder="https://..."></div>
<button class="btn btn-main">Simpan</button> <a href="/admin/barang" class="btn btn-outline-secondary">Batal</a>
</form></div></div></div>
<?= $this->endSection() ?>
