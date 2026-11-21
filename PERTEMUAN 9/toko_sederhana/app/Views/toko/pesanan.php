<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="card p-4">
<h2 class="text-success">Pesanan Berhasil Dibuat</h2>
<p>Simpan kode pesanan berikut untuk melihat status pesanan.</p>
<h4><?= esc($pesanan['kode_pesanan']) ?></h4>
<p>Nama: <?= esc($pesanan['nama_pembeli']) ?><br>Status: <b><?= esc($pesanan['status']) ?></b></p>
<table class="table"><thead><tr><th>Barang</th><th>Jumlah</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($detail as $d): ?><tr><td><?= esc($d['nama_barang']) ?></td><td><?= $d['jumlah'] ?></td><td>Rp <?= number_format($d['subtotal'],0,',','.') ?></td></tr><?php endforeach ?>
</tbody></table>
<h5 class="text-end">Total: Rp <?= number_format($pesanan['total'],0,',','.') ?></h5>
<a href="/" class="btn btn-main align-self-start">Kembali ke Toko</a>
</div>
<?= $this->endSection() ?>
