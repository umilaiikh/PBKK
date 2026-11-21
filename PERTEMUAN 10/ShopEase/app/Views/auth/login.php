<div class="row justify-content-center"><div class="col-md-5"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
<h3 class="mb-1">Selamat datang</h3><p class="text-muted">Login ke akun ShopEase kamu.</p>
<form method="post" action="/login"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required value="<?= old('email') ?>"></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-shop w-100">Login</button></form>
<p class="mt-3 mb-0 small">Belum punya akun? <a href="/register">Daftar di sini</a></p>
<div class="small text-muted mt-3">Demo: admin@shopease.test / password</div>
</div></div></div></div>
