<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="card p-4"><div class="row g-4 align-items-center">
<div class="col-md-5">
<?php if ($barang['gambar']): ?><img src="<?= esc($barang['gambar']) ?>" class="img-fluid rounded" alt="<?= esc($barang['nama']) ?>"><?php else: ?><div class="product-img rounded d-flex align-items-center justify-content-center">Foto Barang</div><?php endif ?>
</div>
<div class="col-md-7">
<h2><?= esc($barang['nama']) ?></h2><h4 class="price">Rp <?= number_format($barang['harga'],0,',','.') ?></h4>
<p>Stok tersedia: <?= esc($barang['stok']) ?></p><p><?= nl2br(esc($barang['deskripsi'])) ?></p>
<form method="post" action="/keranjang/tambah/<?= $barang['id'] ?>"><?= csrf_field() ?><button class="btn btn-main" <?= $barang['stok'] < 1 ? 'disabled' : '' ?>>Tambah ke Keranjang</button> <a href="/" class="btn btn-outline-secondary">Kembali</a></form>
</div></div></div>
<?= $this->endSection() ?>
