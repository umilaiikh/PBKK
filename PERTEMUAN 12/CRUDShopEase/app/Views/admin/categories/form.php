<?php $editing = !empty($category); ?>
<div class="mb-4"><a href="<?= base_url('admin/categories') ?>" class="text-decoration-none text-success">← Kembali ke kategori</a><h2 class="mt-2 mb-1"><?= $editing ? 'Edit Kategori' : 'Tambah Kategori' ?></h2></div>
<div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><form method="post" action="<?= $editing ? base_url('admin/categories/update/' . $category['id']) : base_url('admin/categories/store') ?>"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama Kategori</label><input class="form-control" name="name" required minlength="3" maxlength="100" value="<?= esc(old('name', $category['name'] ?? '')) ?>"></div>
<div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description" rows="4"><?= esc(old('description', $category['description'] ?? '')) ?></textarea></div>
<button class="btn btn-shop"><?= $editing ? 'Simpan Perubahan' : 'Tambah Kategori' ?></button> <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">Batal</a>
</form></div></div>
