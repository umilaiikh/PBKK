<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card p-4">
<h3 class="fw-bold mb-3">Login Admin</h3>
<form method="post" action="/admin/login"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" value="<?= old('username') ?>" required></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-main w-100">Login</button>
</form><a href="/" class="text-center mt-3">Kembali ke toko</a>
</div></div></div>
<?= $this->endSection() ?>
