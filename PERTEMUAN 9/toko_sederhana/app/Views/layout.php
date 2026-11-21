<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'Toko Sederhana') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f7fb;color:#263238}.navbar{background:#173b57}.navbar-brand,.nav-link{color:#fff!important}
.hero{background:linear-gradient(120deg,#173b57,#287b83);color:white;border-radius:18px;padding:38px}
.card{border:0;border-radius:14px;box-shadow:0 5px 18px #172b4d12}.product-img{height:175px;object-fit:cover;background:#e9eef2}
.btn-main{background:#176b70;color:#fff}.btn-main:hover{background:#10565a;color:#fff}
.price{color:#176b70;font-weight:700}.footer{color:#74818b}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4">
<div class="container">
<a class="navbar-brand fw-bold" href="/">TOKO SEDERHANA</a>
<div class="d-flex gap-3">
<a class="nav-link" href="/">Beranda</a>
<a class="nav-link" href="/keranjang">Keranjang</a>
<a class="nav-link" href="/admin/login">Admin</a>
</div>
</div>
</nav>
<main class="container pb-5">
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif ?>
<?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div><?php endif ?>
<?php if (session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div><?php endif ?>
<?= $this->renderSection('content') ?>
</main>
<footer class="container text-center footer py-4">© <?= date('Y') ?> Toko Sederhana</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
