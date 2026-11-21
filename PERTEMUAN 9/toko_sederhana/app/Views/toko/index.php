<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="hero mb-4">
<h1 class="fw-bold">Selamat Datang di Toko Sederhana</h1>
<p class="mb-0">Temukan barang kebutuhanmu dengan mudah dan praktis.</p>
</div>
<h3 class="fw-bold mb-3">Daftar Barang</h3>
<div class="row g-4">
<?php foreach ($barang as $b): ?>
<div class="col-6 col-md-4 col-lg-3">
<div class="card h-100 overflow-hidden">
<?php if ($b['gambar']): ?><img class="product-img card-img-top" src="<?= esc($b['gambar']) ?>" alt="<?= esc($b['nama']) ?>"><?php else: ?><div class="product-img d-flex align-items-center justify-content-center text-secondary">Foto Barang</div><?php endif ?>
<div class="card-body d-flex flex-column">
<h5><?= esc($b['nama']) ?></h5>
<div class="price mb-1">Rp <?= number_format($b['harga'],0,',','.') ?></div>
<small class="text-secondary mb-3">Stok: <?= esc($b['stok']) ?></small>
<a href="/barang/<?= $b['id'] ?>" class="btn btn-outline-secondary mt-auto mb-2">Detail</a>
<form method="post" action="/keranjang/tambah/<?= $b['id'] ?>">
<?= csrf_field() ?>
<button class="btn btn-main w-100" <?= $b['stok'] < 1 ? 'disabled' : '' ?>>Tambah ke Keranjang</button>
</form>
</div></div></div>
<?php endforeach ?>
<?php if (!$barang): ?><p>Belum ada barang tersedia.</p><?php endif ?>
</div>
<?= $this->endSection() ?>
