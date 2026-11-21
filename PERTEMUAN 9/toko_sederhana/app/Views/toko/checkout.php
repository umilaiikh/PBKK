<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="fw-bold mb-3">Checkout</h2>
<div class="row g-4"><div class="col-md-7"><div class="card p-4">
<h5>Data Pembeli</h5>
<form method="post" action="/checkout/proses"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama lengkap</label><input name="nama_pembeli" class="form-control" value="<?= old('nama_pembeli') ?>" required></div>
<div class="mb-3"><label class="form-label">Nomor telepon</label><input name="telepon" class="form-control" value="<?= old('telepon') ?>" required></div>
<div class="mb-3"><label class="form-label">Alamat pengiriman</label><textarea name="alamat" class="form-control" rows="4" required><?= old('alamat') ?></textarea></div>
<button class="btn btn-main">Buat Pesanan</button>
</form></div></div>
<div class="col-md-5"><div class="card p-4"><h5>Ringkasan Belanja</h5>
<?php foreach($items as $i): ?><div class="d-flex justify-content-between border-bottom py-2"><span><?= esc($i['nama']) ?> × <?= $i['jumlah'] ?></span><b>Rp <?= number_format($i['subtotal'],0,',','.') ?></b></div><?php endforeach ?>
<h5 class="mt-3 text-end">Total Rp <?= number_format($total,0,',','.') ?></h5></div></div></div>
<?= $this->endSection() ?>
