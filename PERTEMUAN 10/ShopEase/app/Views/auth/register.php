<div class="row justify-content-center"><div class="col-md-6"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
<h3>Buat akun</h3><p class="text-muted">Daftar sebagai customer ShopEase.</p>
<form method="post" action="/register"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name" required value="<?= old('name') ?>"></div>
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required value="<?= old('email') ?>"></div>
<div class="mb-3"><label class="form-label">Password (min. 6 karakter)</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-shop w-100">Daftar</button></form>
</div></div></div></div>
