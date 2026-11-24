<?php $editing = !empty($employee); ?>
<div class="mb-4"><a href="<?= base_url('admin/employees') ?>" class="text-decoration-none text-success">← Kembali ke data karyawan</a><h2 class="mt-2 mb-1"><?= $editing ? 'Edit Karyawan' : 'Tambah Karyawan' ?></h2><p class="text-muted mb-0">Isi nama, alamat, dan pekerjaan karyawan.</p></div>
<div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><form method="post" action="<?= $editing ? base_url('admin/employees/update/' . $employee['id']) : base_url('admin/employees/store') ?>"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama Karyawan</label><input class="form-control" name="name" required minlength="3" maxlength="100" value="<?= esc(old('name', $employee['name'] ?? '')) ?>"></div>
<div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="address" rows="3" required><?= esc(old('address', $employee['address'] ?? '')) ?></textarea></div>
<div class="mb-3"><label class="form-label">Pekerjaan</label><input class="form-control" name="job" required maxlength="100" value="<?= esc(old('job', $employee['job'] ?? '')) ?>"></div>
<button class="btn btn-shop"><?= $editing ? 'Simpan Perubahan' : 'Tambah Karyawan' ?></button> <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary">Batal</a>
</form></div></div>
